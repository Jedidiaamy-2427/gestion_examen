import { Routes } from '@angular/router';
import { AuthGuard } from './core/guard/auth.guard';

export const routes: Routes = [
    { path: '', redirectTo: 'dashboard', pathMatch: 'full' },
    { path: 'login', loadComponent:() => import('./components/auth/login.component').then(m => m.LoginComponent)},
    { path: 'register', loadComponent: () => import('./components/register/register.component').then(m => m.RegisterComponent)},
    { path: '', loadComponent: () => import('./app.component').then(m => m.AppComponent), 
       canActivate: [AuthGuard],
       children: [
            { path: 'dashboard', loadComponent: () => import('./components/dashboard/dashboard.component').then(m => m.DashboardComponent)},
            { path: 'exams', loadComponent: () => import('./components/exam/exam.component').then(m => m.ExamComponent)},
            { path: 'exams/nouveau', loadComponent: () => import('./components/exam/exam.component').then(m => m.ExamComponent)},
            { path: 'exams/:id/edit', loadComponent: () => import('./components/exam/exam.component').then(m => m.ExamComponent)},
            { path: 'students', loadComponent: () => import('./components/student/student.component').then(m => m.StudentComponent)},
       ]},
    { path: '**', redirectTo: 'dashboard' }
];
