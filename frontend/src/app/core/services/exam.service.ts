import { Injectable, signal } from '@angular/core';
import { environment } from '../../environments/environment.development';
import { ExamInterface } from '../../shared/interfaces/exam.interface';
import { HttpClient } from '@angular/common/http';
import { tap } from 'rxjs';
import { StudentService } from './student.service';

@Injectable({
  providedIn: 'root'
})
export class ExamService { 
    private apiUrl = `${environment.apiUrl}/exams`
    _exams = signal<ExamInterface[] | any[]>([])
    exams = this._exams.asReadonly();

    private _exam = signal<ExamInterface | null>(null);
    exam = this._exam.asReadonly();


  constructor(private http: HttpClient, private studentService: StudentService) { }

  addExam(exam: {student: string; location: string; date: Date; time: Date; status: string}) {
    return this.http.post<ExamInterface>(this.apiUrl, exam).pipe(
      tap(() => this.getAll().subscribe())
    )
  }

  getAll() {
    return this.http.get<any>(this.apiUrl).pipe(
      tap(res => {
        const list = res.member

    
        this.studentService.getAll().subscribe(students => {

          const mapStudents = new Map<number, string>(
            students.member.map((s: any) => [s.id, s.name])
          );
        
          const mapped = list.map((exam: any) => {
            const studentId = Number(exam.student.split("/").pop());
            return {
              ...exam,
              studentName: mapStudents.get(studentId) || "Inconnu"
            };
          });

          const sorted = [...mapped].sort((a, b) => new Date(a.date).getTime() - new Date(b.date).getTime()
          );
          this._exams.set(sorted);
        })     
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
