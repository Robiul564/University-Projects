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

    this.init();
  }

  init() {
    document.getElementById('prevMonth').addEventListener('click', () => this.changeMonth(-1));
    document.getElementById('nextMonth').addEventListener('click', () => this.changeMonth(1));
    this.render();
  }

  changeMonth(direction) {
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

  render() {
    const currentDateEl = document.querySelector('.current-date');
    currentDateEl.textContent = `${this.monthNames[this.currentMonth]} ${this.currentYear}`;

    const calendarGrid = document.querySelector('.calendar-grid');
    calendarGrid.querySelectorAll('.day').forEach(day => day.remove());

    const daysInMonth = new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
    const firstDay = new Date(this.currentYear, this.currentMonth, 1).getDay();

    // Previous month days
    const prevMonthDays = firstDay > 0 ? firstDay : 0;
    for (let i = 0; i < prevMonthDays; i++) {
      this.createDayElement(calendarGrid, '', true);
    }

    // Current month days
    for (let i = 1; i <= daysInMonth; i++) {
      const isToday = this.isToday(i, this.currentMonth, this.currentYear);
      const isSelected = this.selectedDate && this.selectedDate.getDate() === i &&
          this.selectedDate.getMonth() === this.currentMonth &&
          this.selectedDate.getFullYear() === this.currentYear;

      const day = this.createDayElement(calendarGrid, i, false, isToday, isSelected);
      day.addEventListener('click', () => {
        this.selectedDate = new Date(this.currentYear, this.currentMonth, i);
        this.render();
      });
    }
  }

  isToday(date, month, year) {
    const today = new Date();
    return date === today.getDate() && month === today.getMonth() && year === today.getFullYear();
  }

  createDayElement(container, content, inactive = false, today = false, selected = false) {
    const day = document.createElement('button');
    day.classList.add('day');
    if (inactive) day.classList.add('inactive');
    if (today) day.classList.add('today');
    if (selected) day.classList.add('selected');
    day.textContent = content;
    container.appendChild(day);
    return day;
  }
}

// Initialize the calendar
new Calendar();
