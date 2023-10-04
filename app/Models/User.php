<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use DateTime;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * The courses that belong to the user.
     */
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class)->withPivot('status');
    }

    /**
     * Get the lessions for the User.
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class)->withPivot('status');
    }    

    public function hasAttendedLesson($lessonId)
    {
        return $this->lessions()->where('lession_id', $lessonId)->exists();
    }

    public function statusLesson($lessonId)
    {
        return $this->lessions()->where('lession_id', $lessonId)->first();
    }

    /**
     * Get the lessions for the User.
     */
    public function lessions(): BelongsToMany
    {
        return $this->belongsToMany(Lession::class)->withPivot(['status']);
    }

    /**
     * The roles that belong to the user.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole($role)
    {
        return $this->roles()->where('roles.name', $role)->exists();
    }

    /**
     * The schools that belong to the user-operator.
     */
    
    public function schools(): BelongsToMany
    {
        return $this->belongsToMany(University::class);
    }

    public function test($subjectId,$type)
    {
        $lession_id = $this->lessions()->where('lessions.subject_id',$subjectId)->where('lessions.type',$type)->first();

        $quizResults = DB::table('quizzes')
                        ->join('users', 'quizzes.user_id', '=', 'users.id')
                        ->where('quizzes.lession_id', $lession_id)
                        ->whereColumn('quizzes.answer', 'quizzes.correct')
                        ->select('users.name', 'users.surname', DB::raw('SUM(quizzes.score) as total_score'))
                        ->groupBy('users.name','users.surname')
                        ->get();
        return $quizResults;
    }

    public function hasAttendedTeste($testeId)
    {
        return $this->testes()->where('teste_id', $testeId)->exists();
    }

    public function statusTeste($testeId)
    {
        return $this->testes()->where('teste_id', $testeId)->first();
    }

    /**
     * Get the lessions for the User.
     */
    public function testes(): BelongsToMany
    {
        return $this->belongsToMany(Teste::class)->withPivot(['status','started_at','ended_at']);
    }    

    public function results($subjectId)
    {
        $tI = $this->testes()->where('testes.subject_id', $subjectId)->where('testes.name', 'Teste I')->first();
        $tII = $this->testes()->where('testes.subject_id', $subjectId)->where('testes.name', 'Teste II')->first();
        $media = 0;
        $testeI = 0;
        $testeII = 0;
        $status = '';
        $finally   = false;
        $date = '';
        if(isset($tI) && isset($tII)){
            $testeI = $tI->score($this->id);
            $testeII = $tII->score($this->id);
            $media = ($testeI + $testeII) / 2;
        }
        if($media < 10){
            $status = 'Excluído';
            $date = $tII ? date_format(new DateTime($tII->pivot->ended_at),'d-m-Y') : '';
            $finally = true;
        }elseif ($media >= 14) {
            $status = 'Dispensado';
            $date = $tII ? date_format(new DateTime($tII->pivot->ended_at),'d-m-Y') : '';
            $finally = true;
        }else {
            $exameNormal = $this->testes()->where('testes.subject_id', $subjectId)->where('testes.name', 'Exame Normal')->first();
            if($exameNormal->pivot->status === 'Concluída'){
                $notaExameNormal = $exameNormal->score($this->id);
                if($notaExameNormal >= 10){
                    $nMedia = $notaExameNormal + $media;
                    $status = 'Aprovado';
                    $media = $nMedia/2;
                    $date = date_format(new DateTime($exameNormal->pivot->ended_at),'d-m-Y');
                    $finally = true;
                }else{
                    $status = 'Reprovado';
                    $finally = false;
                    $exameRecorrencia = $this->testes()->where('testes.subject_id', $subjectId)->where('testes.name', 'Exame de Recorrência')->first();
                    if($exameRecorrencia->pivot->status === 'Concluída'){
                        $notaExameRecorrencia = $exameRecorrencia->score($this->id);
                        if($notaExameRecorrencia >= 10){
                            $status = 'Aprovado Recorrência';
                            $nMedia = $notaExameRecorrencia + $media;
                            $media = $nMedia/2;
                            $date = date_format(new DateTime($exameRecorrencia->pivot->ended_at),'d-m-Y');
                            $finally = true;
                        }else{
                            $status = 'Reprovado Recorrência';
                            $date = date_format(new DateTime($exameRecorrencia->pivot->ended_at),'d-m-Y');
                            $finally = true;
                        }
                    }
                }
            }else{
                $status = 'Admitido';
                $finally = false;
            }

        }
        return [
            'media'   => $media,
            'status'  => $status,
            'date'    => $date,
            'finally' => $finally
        ];
    }

    public function universities(): BelongsToMany
    {
        return $this->belongsToMany(University::class);
    }

    public function activeSchool()
    {
        $univeristies = $this->schools;
        return $univeristies[0];
    }
}
