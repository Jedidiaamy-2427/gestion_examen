import { CommonModule } from "@angular/common";
import { Component, signal } from "@angular/core";
import { Router } from "@angular/router";
import { FormsModule } from "@angular/forms";

@Component({
    selector: 'app-login',
    standalone: true,
    templateUrl: './login.component.html',
    imports: [CommonModule, FormsModule]
})
export class LoginComponent {
    protected username = signal<null | string>(null);
    protected password = signal<null | string>(null);

    constructor(private router: Router) {
        
    }

    protected login() {
        console.log(this.username(), this.password());
    }

    protected onLogin() {
        
    }


}