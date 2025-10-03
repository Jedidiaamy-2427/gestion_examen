import { Injectable, signal } from '@angular/core';
import { environment } from '../../environments/environment.development';
import { HttpClient } from '@angular/common/http';
import { tap } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class StudentService {
  private apiUrl = `${environment.apiUrl}/students`
  private _students = signal<any[]>([])
  students = this._students.asReadonly();

  constructor(private http: HttpClient) { }

  getAll() {
      return this.http.get<any>(this.apiUrl).pipe(
        tap(res => {
          this._students.set(res.member);
        })     
      );  
  }

  getById(id:number) {
    return this.http.get<[]>(`${this.apiUrl}/${id}`).subscribe();
  }

}
