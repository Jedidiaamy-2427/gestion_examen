import { Injectable, signal } from '@angular/core';
import { environment } from '../../environments/environment.development';
import { HttpClient } from '@angular/common/http';
import { Observable, tap } from 'rxjs';
import { Student, StudentResponseObject } from '../../shared/interfaces/student.interface';
import { ResponseObjectData } from '../../shared/interfaces/Response.interface';

@Injectable({
  providedIn: 'root'
})
export class StudentService {
  private apiUrl = `${environment.apiUrl}/students`
  private _students = signal<any[]>([])
  students = this._students.asReadonly();

  constructor(private http: HttpClient) { }

  getAll():Observable<ResponseObjectData> {
      return this.http.get<ResponseObjectData>(this.apiUrl).pipe(
        tap(res => {
          this._students.set(res.member);
        })     
      );
  }

  getById(id:number): Observable<StudentResponseObject> {
    return this.http.get<StudentResponseObject>(`${this.apiUrl}/${id}`);
  }

}
