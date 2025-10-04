import { Component, computed, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ExamService } from '../../core/services/exam.service';
import { ExamInterface } from '../../shared/interfaces/exam.interface';

@Component({
  selector: 'app-dashboard',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.css'],
})
export class DashboardComponent {

  protected exams = signal<ExamInterface[]>([]);

  protected stats = computed(() => {
    const exams = this.exams();
    return {
      confirmed: exams.filter(e => e.status === 'Confirmé').length,
      toOrganize: exams.filter(e => e.status === 'À organiser').length,
      cancelled: exams.filter(e => e.status === 'Annulé').length,
      searching: exams.filter(e => e.status === 'En recherche de place').length,
    };
  });

  constructor(private examService: ExamService) {}

  ngOnInit() {
    this.examService.getAll().subscribe({
      next: (dataExam) => {
        const list = dataExam.member || [];
        this.exams.set(list);
      },
      error: (err) => console.error('Erreur de chargement des examens:', err),
    });
  }
}
