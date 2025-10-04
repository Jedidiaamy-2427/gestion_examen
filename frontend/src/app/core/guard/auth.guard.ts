import { Injectable, inject } from '@angular/core';
import { CanActivate, Router } from '@angular/router';
import { AuthService } from '../services/auth.service';


@Injectable({ providedIn: 'root' })
export class AuthGuard implements CanActivate {
  auth = inject(AuthService);
  router = inject(Router);


  canActivate(): boolean {
    if (!this.auth.token && this.auth.token == undefined) {
      this.auth.logout()
      this.router.navigate(['/login']);
      return false; 
    }
    return true;
  }
}     
