import { Component } from '@angular/core';
import { ExamService } from '../../core/services/exam.service';
import { Router } from '@angular/router';
import { ExamInterface } from '../../shared/interfaces/exam.interface';

interface ExamStats {
  confirmed: number;
  toOrganize: number;
  cancelled: number;
  searching: number;
}

@Component({
  selector: 'app-dashboard',
  imports: [],
  templateUrl: './dashboard.component.html',
  styleUrl: './dashboard.component.css'
})
export class DashboardComponent {

  stats: ExamStats = {
    confirmed: 0,
    toOrganize: 0,
    cancelled: 0,
    searching: 0
  };

  constructor(private examService: ExamService, private router: Router)
  {}

    ngOnInit() {
      this.examService.getAll().subscribe({
        next: async (dataExam) => {
          const list =  dataExam.member

          this.computeStats(list)
        },
        error: (err) =>  {
          console.log('ERROR', err)
        }
      })
      
  }

  private computeStats(exams: ExamInterface[]): void {
    this.stats = { confirmed: 0, toOrganize: 0, cancelled: 0, searching: 0 };
    exams.forEach((exam) => {
      switch (exam.status) {
        case 'Confirmé':
          this.stats.confirmed++;
          break;
        case 'À organiser':
          this.stats.toOrganize++;
          break;
        case 'Annulé':
          this.stats.cancelled++;
          break;
        case 'En recherche de place':
          this.stats.searching++;
          break;
        default:
          break; // ignore unexpected status
      }
    });
  }
}


