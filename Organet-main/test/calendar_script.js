class Calendar {
    constructor() {
      this.date = new Date();
      this.currentMonth = this.date.getMonth();
      this.currentYear = this.date.getFullYear();
      this.selectedDate = null;
      
      this.monthNames = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
      ];
  
      this.setupEventListeners();
      this.render();
    }
  
    setupEventListeners() {
      document.getElementById('prevMonth').addEventListener('click', () => {
        this.navigateMonth(-1);
      });
  
      document.getElementById('nextMonth').addEventListener('click', () => {
        this.navigateMonth(1);
      });
    }
  
    navigateMonth(direction) {
      this.currentMonth += direction;
      
      if (this.currentMonth > 11) {
        this.currentMonth = 0;
        this.currentYear++;
      } else if (this.currentMonth < 0) {
        this.currentMonth = 11;
        this.currentYear--;
      }
      
      this.render();
    }
  
    getDaysInMonth(month, year) {
      return new Date(year, month + 1, 0).getDate();
    }
  
    getFirstDayOfMonth(month, year) {
      return new Date(year, month, 1).getDay();
    }
  
    isToday(date, month, year) {
      const today = new Date();
      return date === today.getDate() && 
             month === today.getMonth() && 
             year === today.getFullYear();
    }
  
    render() {
      const currentDate = document.querySelector('.current-date');
      currentDate.textContent = `${this.monthNames[this.currentMonth]} ${this.currentYear}`;
  
      const daysInMonth = this.getDaysInMonth(this.currentMonth, this.currentYear);
      const firstDay = this.getFirstDayOfMonth(this.currentMonth, this.currentYear);
      
      const calendarGrid = document.querySelector('.calendar-grid');
      const existingDays = calendarGrid.querySelectorAll('.day');
      existingDays.forEach(day => day.remove());
  
      // Previous month's days
      const prevMonth = this.currentMonth - 1 < 0 ? 11 : this.currentMonth - 1;
      const prevYear = prevMonth === 11 ? this.currentYear - 1 : this.currentYear;
      const daysInPrevMonth = this.getDaysInMonth(prevMonth, prevYear);
      
      for (let i = firstDay - 1; i >= 0; i--) {
        const day = document.createElement('button');
        day.classList.add('day', 'inactive');
        day.textContent = daysInPrevMonth - i;
        calendarGrid.appendChild(day);
      }
  
      // Current month's days
      for (let i = 1; i <= daysInMonth; i++) {
        const day = document.createElement('button');
        day.classList.add('day');
        
        if (this.isToday(i, this.currentMonth, this.currentYear)) {
          day.classList.add('today');
        }
        
        if (this.selectedDate && 
            i === this.selectedDate.getDate() && 
            this.currentMonth === this.selectedDate.getMonth() && 
            this.currentYear === this.selectedDate.getFullYear()) {
          day.classList.add('selected');
        }
  
        day.textContent = i;
        
        day.addEventListener('click', () => {
          this.selectedDate = new Date(this.currentYear, this.currentMonth, i);
          document.querySelectorAll('.day').forEach(d => d.classList.remove('selected'));
          day.classList.add('selected');
        });
        
        calendarGrid.appendChild(day);
      }
  
      // Next month's days
      const totalDays = firstDay + daysInMonth;
      const remainingDays = 42 - totalDays;
      
      for (let i = 1; i <= remainingDays; i++) {
        const day = document.createElement('button');
        day.classList.add('day', 'inactive');
        day.textContent = i;
        calendarGrid.appendChild(day);
      }
    }
  }
  
  // Initialize the calendar
  new Calendar();
  