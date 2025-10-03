import { Injectable, signal } from '@angular/core';
import { tap } from 'rxjs';
import { environment } from '../../environments/environment.development';
import { HttpClient } from '@angular/common/http';


export interface AuthResponse {
  username: string;
  email: string;
  token: string;
  refresh_token: string;
}

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private apiUrl = environment.apiUrl;
  public _token = signal<string | null>(null);
  public _refresh = signal<string | null>(null);
  user = signal<AuthResponse | any | null>(null);

  constructor(private http: HttpClient) {
    const token = localStorage.getItem('auth_token');
    const refresh = localStorage.getItem('refresh_token');
    if (token) this._token.set(token);
    if (refresh) this._refresh.set(refresh);
  }

    login(username: string, password: string) {
    return this.http.post<AuthResponse>(`${this.apiUrl}/login`, { username, password })
      .pipe(tap(res => {
        this._token.set(res.token);
        this._refresh.set(res.refresh_token);
        localStorage.setItem('auth_token', res.token);
        localStorage.setItem('refresh_token', res.refresh_token);
        this.user.set({ username: res.username, email: res.email, token: res.token, refreshToken: res.refresh_token });
      }));
  }
}
