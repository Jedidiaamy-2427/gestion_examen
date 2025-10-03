import { Injectable, signal } from '@angular/core';
import { environment } from '../../environments/environment.development';
import { ExamInterface } from '../../shared/interfaces/exam.interface';
import { HttpClient } from '@angular/common/http';
import { tap } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ExamService { 
    private apiUrl = `${environment.apiUrl}/exams`
    private _exams = signal<ExamInterface[]>([])
    exams = this._exams.asReadonly();

    private _exam = signal<ExamInterface | null>(null);
    exam = this._exam.asReadonly();


  constructor(private http: HttpClient) { }

  addExam(exam: {student: string; location: string; date: Date; time: Date; status: string}) {
    return this.http.post<ExamInterface>(this.apiUrl, exam).pipe(
      tap(() => this.getAll().subscribe())
    )
  }

  getAll() {
    return this.http.get<any>(this.apiUrl).pipe(
      tap(res => {
        const list = res.member
        const sorted = [...list].sort((a, b) => new Date(a.date).getTime() - new Date(b.date).getTime()
        );
        this._exams.set(sorted);
      })     
    );
  } 

  updateExam(id:number, dto: {student: string; location: string; date: Date; time: Date; status: string}) {
    return this.http.put<ExamInterface>(`${this.apiUrl}/${id}`, dto).pipe(
      tap(() => this.getAll().subscribe())
    )
  }

  removeExam(id:number) {
    return this.http.delete<ExamInterface>(`${this.apiUrl}/${id}`).pipe(
      tap(() => this.getAll().subscribe())
    )
  }

  updateStatus(id:number, status: string) {
    return this.http.patch<ExamInterface>(`${this.apiUrl}/${id}`, { status }).pipe(
      tap(() => this.getAll().subscribe())
    )
  }



}
