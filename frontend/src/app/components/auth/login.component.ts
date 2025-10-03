import { CommonModule } from "@angular/common";
import { Component, signal } from "@angular/core";
import { Router } from "@angular/router";
import { FormBuilder, FormGroup, FormsModule, ReactiveFormsModule, Validators } from "@angular/forms";
import { AuthService } from "../../core/services/auth.service";
import { AuthResponse } from "../../shared/interfaces/auth-reponse.interface";

@Component({
    selector: 'app-login',
    standalone: true,
    templateUrl: './login.component.html',
    imports: [CommonModule, FormsModule, ReactiveFormsModule]
})
export class LoginComponent {
    form:FormGroup;
    protected username = signal<null | string>(null);
    protected password = signal<null | string>(null);
    protected loginError = signal<string | null>(null);

    constructor(private fb: FormBuilder, 
                private router: Router,
                private authService: AuthService
    ) {
        this.form = this.fb.group({
            email: ['', Validators.required],
            password: ['', Validators.required]
        });
    }

    protected onLogin() {
        if (this.form.invalid) return;
        const { email, password } = this.form.value as { email: string; password: string };

        this.authService.login(email, password).subscribe({
        next: (user: AuthResponse | any) => {
            this.loginError.set(null);
            if ('token' in user) {
            this.router.navigate(['/projects']);
            } else {
            this.form.setErrors({ invalidLogin: true });
            }
        },
        error: (err) => {
            this.loginError.set(err.error?.title);
            this.form.setErrors({ invalidLogin: true });
        }
        });
    }


}