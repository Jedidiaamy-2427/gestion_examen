import { CommonModule } from '@angular/common';
import { Component, inject, signal } from '@angular/core';
import { Router, RouterOutlet } from '@angular/router';
import { AuthService } from './core/services/auth.service';
import { jwtDecode } from 'jwt-decode';

@Component({
  selector: 'app-root',
  imports: [RouterOutlet, CommonModule],

  templateUrl: './app.component.html',
  styleUrl: './app.component.css'
})
export class AppComponent {
  title = signal('frontend');
  router = inject(Router);
  auth =  inject(AuthService)

  logout() {
    this.auth.logout();
    this.router.navigate(['/login']);
  }

    getUsernameToken() {
      const token = localStorage.getItem('auth_token');
      if (token) {
        try {
          const decodedToken: any = jwtDecode(token);
          return decodedToken.username || 'Unknown User';
        } catch (error) {
          console.error('Error decoding token:', error);
          return 'Invalid Token';
        }
      }
    }

}
