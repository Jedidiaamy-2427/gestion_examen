import { HttpErrorResponse, HttpInterceptorFn } from '@angular/common/http';
import { inject } from '@angular/core';
import { catchError, switchMap, throwError } from 'rxjs';
import { AuthService } from './auth.service';


export const AuthInterceptor: HttpInterceptorFn = (req, next) => {
  const auth = inject(AuthService);
  const token = localStorage.getItem('auth_token');

  if (req.url.includes('/api/refresh_token') || req.url.includes('/api/logout')) {
    return next(req);
  }

  let clonedReq = req;
  if (token && token != undefined) {
    clonedReq = req.clone({
      setHeaders: { Authorization: `Bearer ${token}` }
    });
  }

  return next(clonedReq).pipe(
    catchError((err: HttpErrorResponse) => {
      if (err.status === 401 && auth.refreshToken) {
        return auth.refresh().pipe(
          switchMap(() => {
            const newToken = auth.token;
            const retried = newToken
              ? req.clone({ setHeaders: { Authorization: `Bearer ${newToken}` } })
              : req;
            return next(retried);
          })
        );
      }
      return throwError(() => err);
    })
  );
};

