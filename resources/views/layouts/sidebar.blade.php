<div class="sidebar" id="sidebar" style="background-color: #000D13;">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
            @if(Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Operador'))
                <li class="{{set_active(['/'])}}">
                    <a href="{{ route('home') }}"><i class="feather-grid"></i>
                        <span> Painel Administrativo</span>
                    </a>
                </li>
                <li class="{{set_active(['universities','universities/create'])}}">
                    <a href="{{ route('universities.index') }}"><i class="fas fa-building"></i>
                        <span> Escolas</span>
                    </a>
                </li>
                <li class="{{set_active(['courses','courses/create'])}}">
                    <a href="{{ route('courses.index') }}"><i class="fas fa-chalkboard-teacher"></i>
                        <span> Cursos</span>
                    </a>
                </li>
                <li class="{{set_active(['schools','schools/create'])}}">
                    <a href="{{ route('schools.index') }}"><i class="fas fa-graduation-cap"></i>
                        <span> Operadores da Escola</span>
                    </a>
                </li>
            @endif
            @if(Auth::user()->hasRole('Administrador'))
                <li class="{{set_active(['operators','operators/create'])}}">
                    <a href="{{ route('operators.index') }}"><i class="fas fa-user-secret"></i>
                        <span> Operadores do Sistema</span>
                    </a>
                </li>
            @endif
            @if(Auth::user()->hasRole('Escola'))
                <li class="{{set_active(['/'])}}">
                    <a href="{{ route('home') }}"><i class="fas fa-graduation-cap"></i>
                        <span> Visualizar Estudantes</span>
                    </a>
                </li>
                <li class="{{set_active(['courses/student/create'])}}">
                    <a href="{{ route('courses.student.create') }}"><i class="fas fa-plus"></i>
                        <span> Estudante - Disciplina</span>
                    </a>
                </li>
                <li class="{{set_active(['students','students/create'])}}">
                    <a href="{{ route('students.index') }}"><i class="fas fa-user-plus"></i>
                        <span> Adicionar Estudantes</span>
                    </a>
                </li>
            @endif
            </ul>
        </div>
    </div>
</div>