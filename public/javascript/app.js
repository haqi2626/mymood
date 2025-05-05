// Calendar functionality
const calendarContainer = document.getElementById("calendar")
let currentDate = new Date()
let selectedDate = null
let activePopup = null

function renderCalendar() {
  // Clear previous calendar
  calendarContainer.innerHTML = ""

  // Create calendar header
  const calendarHeader = document.createElement("div")
  calendarHeader.className = "calendar-header"

  const monthYearTitle = document.createElement("h2")
  monthYearTitle.textContent = new Intl.DateTimeFormat("id-ID", {
    month: "long",
    year: "numeric",
  }).format(currentDate)

  const navButtons = document.createElement("div")
  navButtons.className = "calendar-nav"

  const prevButton = document.createElement("button")
  prevButton.className = "calendar-nav-btn"
  prevButton.innerHTML = '<i class="fas fa-chevron-left"></i>'
  prevButton.addEventListener("click", previousMonth)

  const nextButton = document.createElement("button")
  nextButton.className = "calendar-nav-btn"
  nextButton.innerHTML = '<i class="fas fa-chevron-right"></i>'
  nextButton.addEventListener("click", nextMonth)

  navButtons.appendChild(prevButton)
  navButtons.appendChild(nextButton)

  calendarHeader.appendChild(monthYearTitle)
  calendarHeader.appendChild(navButtons)
  calendarContainer.appendChild(calendarHeader)

  // Create calendar grid
  const calendarGrid = document.createElement("div")
  calendarGrid.className = "calendar-grid"

  // Add day names
  const dayNames = ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"]
  dayNames.forEach((day) => {
    const dayNameElement = document.createElement("div")
    dayNameElement.className = "calendar-day-name"
    dayNameElement.textContent = day
    calendarGrid.appendChild(dayNameElement)
  })

  // Get first day of month and total days
  const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1)
  const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0)
  const totalDays = lastDay.getDate()

  // Add empty cells for days before the first day of month
  const firstDayIndex = firstDay.getDay() // 0 = Sunday, 1 = Monday, etc.
  for (let i = 0; i < firstDayIndex; i++) {
    const emptyDay = document.createElement("div")
    emptyDay.className = "calendar-day empty"
    calendarGrid.appendChild(emptyDay)
  }

  // Add days of the month
  const today = new Date()
  for (let day = 1; day <= totalDays; day++) {
    const date = new Date(currentDate.getFullYear(), currentDate.getMonth(), day)
    const dayElement = document.createElement("div")
    dayElement.className = "calendar-day"
    dayElement.textContent = day

    // Check if this day is today
    if (
      date.getDate() === today.getDate() &&
      date.getMonth() === today.getMonth() &&
      date.getFullYear() === today.getFullYear()
    ) {
      dayElement.classList.add("today")
    }

    // Check if this day is selected
    if (
      selectedDate &&
      date.getDate() === selectedDate.getDate() &&
      date.getMonth() === selectedDate.getMonth() &&
      date.getFullYear() === selectedDate.getFullYear()
    ) {
      dayElement.classList.add("selected")
    }

    // Add click event to show popup
    dayElement.addEventListener("click", (e) => {
      e.stopPropagation()

      // Remove any existing popups
      if (activePopup) {
        activePopup.remove()

        // If clicking the same date, just close the popup
        if (
          selectedDate &&
          date.getDate() === selectedDate.getDate() &&
          date.getMonth() === selectedDate.getMonth() &&
          date.getFullYear() === selectedDate.getFullYear()
        ) {
          selectedDate = null
          renderCalendar()
          return
        }
      }

      // Update selected date
      selectedDate = date

      // Create popup
      const popup = document.createElement("div")
      popup.className = "calendar-popup active"

      const popupDate = document.createElement("div")
      popupDate.className = "calendar-popup-date"
      popupDate.textContent = new Intl.DateTimeFormat("id-ID", {
        day: "numeric",
        month: "long",
        year: "numeric",
      }).format(date)

      const popupInfo = document.createElement("div")
      popupInfo.className = "calendar-popup-info"
      popupInfo.textContent = `Informasi untuk tanggal ${day}`

      popup.appendChild(popupDate)
      popup.appendChild(popupInfo)

      // Position popup at top-left of the day element
      dayElement.appendChild(popup)
      activePopup = popup

      // Update calendar to show selected date
      renderCalendar()
    })

    calendarGrid.appendChild(dayElement)
  }

  calendarContainer.appendChild(calendarGrid)
}

function previousMonth() {
  currentDate = new Date(currentDate.getFullYear(), currentDate.getMonth() - 1, 1)
  selectedDate = null
  if (activePopup) {
    activePopup.remove()
    activePopup = null
  }
  renderCalendar()
}

function nextMonth() {
  currentDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 1)
  selectedDate = null
  if (activePopup) {
    activePopup.remove()
    activePopup = null
  }
  renderCalendar()
}

// Close popup when clicking outside
document.addEventListener("click", () => {
  if (activePopup) {
    activePopup.remove()
    activePopup = null
    selectedDate = null
    renderCalendar()
  }
})

// Initialize calendar
renderCalendar()
