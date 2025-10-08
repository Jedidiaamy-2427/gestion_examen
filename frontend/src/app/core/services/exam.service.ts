import { Injectable, signal } from '@angular/core';
import { environment } from '../../environments/environment.development';
import { ExamInterface, ExamPostInterface } from '../../shared/interfaces/exam.interface';
import { HttpClient } from '@angular/common/http';
import { Observable, of, tap } from 'rxjs';
import { StudentService } from './student.service';
import { ResponseObjectData } from '../../shared/interfaces/Response.interface';

@Injectable({
  providedIn: 'root'
})
export class ExamService { 
    private apiUrl = `${environment.apiUrl}/exams`
    private _exams = signal<ExamInterface[] | any[]>([])
    public exams = this._exams.asReadonly();

    private _exam = signal<ExamInterface | null>(null);
    public exam = this._exam.asReadonly();

  constructor(private http: HttpClient, private studentService: StudentService) { }

  getAll(): Observable<ResponseObjectData> {
    return this.http.get<ResponseObjectData>(this.apiUrl).pipe(
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

  addExam(exam: ExamPostInterface):Observable<ExamInterface | any>  {
    try {
      return this.http.post<ExamInterface>(this.apiUrl, exam)
    } catch (error) {
      return of<any>(error);
    }
  }

  updateExam(id:number, dto: ExamPostInterface):Observable<ExamInterface | any>{
    try {
      return this.http.put<ExamInterface>(`${this.apiUrl}/${id}`, dto)
    } catch (error) {
      return of<any>(error);
    }
  }

  removeExam(id:number): Observable<ExamInterface | any>{
    try {
      return this.http.delete<ExamInterface>(`${this.apiUrl}/${id}`)
    } catch (error) {
      return of<any>(error);
    }
  }

  updateStatus(id:number, status: string): Observable<ExamInterface | any>{
    try {
      return this.http.patch<ExamInterface>(`${this.apiUrl}/${id}`, { status })
    } catch (error) {
      return of<any>(error);
    }
  }

}
