document.addEventListener("DOMContentLoaded", () => {
    // Calendar functionality
    const calendarContainer = document.getElementById("calendar");
    const usersWrapper = document.getElementById("users-wrapper");
    const dateDisplay = document.getElementById("date-display");

    let currentDate = new Date();
    let selectedDate = null;

    // Sample user data - in a real app, this would come from a database
    const usersData = {
        // Format: "YYYY-MM-DD": [array of users]
        "2023-05-09": [
            { name: "John Doe", email: "john.doe@example.com", initials: "JD" },
            {
                name: "Jane Smith",
                email: "jane.smith@example.com",
                initials: "JS",
            },
            {
                name: "Robert Johnson",
                email: "robert.j@example.com",
                initials: "RJ",
            },
        ],
        "2023-05-15": [
            {
                name: "Alice Brown",
                email: "alice.b@example.com",
                initials: "AB",
            },
            {
                name: "Charlie Davis",
                email: "charlie.d@example.com",
                initials: "CD",
            },
        ],
        "2023-05-22": [
            { name: "Eva Wilson", email: "eva.w@example.com", initials: "EW" },
            {
                name: "Frank Miller",
                email: "frank.m@example.com",
                initials: "FM",
            },
            {
                name: "Grace Taylor",
                email: "grace.t@example.com",
                initials: "GT",
            },
            {
                name: "Henry Clark",
                email: "henry.c@example.com",
                initials: "HC",
            },
        ],
    };

    // Function to format date as YYYY-MM-DD
    function formatDateKey(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, "0");
        const day = String(date.getDate()).padStart(2, "0");
        return `${year}-${month}-${day}`;
    }

    // Function to display users for a selected date
    function displayUsersForDate(date) {
        const dateKey = formatDateKey(date);
        const users = usersData[dateKey] || [];

        // Update date display
        dateDisplay.textContent = new Intl.DateTimeFormat("id-ID", {
            day: "numeric",
            month: "long",
            year: "numeric",
        }).format(date);

        // Clear previous content
        usersWrapper.innerHTML = "";

        if (users.length === 0) {
            // No users for this date
            const noUsers = document.createElement("div");
            noUsers.className = "no-selection-message";
            noUsers.innerHTML = `
          <i class="fas fa-user-slash text-gray-400 text-4xl mb-3"></i>
          <p>No users found for this date</p>
        `;
            usersWrapper.appendChild(noUsers);
        } else {
            // Display users
            users.forEach((user) => {
                const userItem = document.createElement("div");
                userItem.className = "user-item";

                userItem.innerHTML = `
            <div class="user-avatar">${user.initials}</div>
            <div class="user-info">
              <div class="user-name">${user.name}</div>
              <div class="user-email">${user.email}</div>
            </div>
          `;

                usersWrapper.appendChild(userItem);
            });
        }
    }

    function renderCalendar() {
        // Clear previous calendar
        calendarContainer.innerHTML = "";

        // Create calendar header
        const calendarHeader = document.createElement("div");
        calendarHeader.className = "calendar-header";

        const monthYearTitle = document.createElement("h2");
        monthYearTitle.textContent = new Intl.DateTimeFormat("id-ID", {
            month: "long",
            year: "numeric",
        }).format(currentDate);

        const navButtons = document.createElement("div");
        navButtons.className = "calendar-nav";

        const prevButton = document.createElement("button");
        prevButton.className = "calendar-nav-btn";
        prevButton.innerHTML = '<i class="fas fa-chevron-left"></i>';
        prevButton.addEventListener("click", previousMonth);

        const nextButton = document.createElement("button");
        nextButton.className = "calendar-nav-btn";
        nextButton.innerHTML = '<i class="fas fa-chevron-right"></i>';
        nextButton.addEventListener("click", nextMonth);

        navButtons.appendChild(prevButton);
        navButtons.appendChild(nextButton);

        calendarHeader.appendChild(monthYearTitle);
        calendarHeader.appendChild(navButtons);
        calendarContainer.appendChild(calendarHeader);

        // Create calendar grid
        const calendarGrid = document.createElement("div");
        calendarGrid.className = "calendar-grid";

        // Add day names
        const dayNames = ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"];
        dayNames.forEach((day) => {
            const dayNameElement = document.createElement("div");
            dayNameElement.className = "calendar-day-name";
            dayNameElement.textContent = day;
            calendarGrid.appendChild(dayNameElement);
        });

        // Get first day of month and total days
        const firstDay = new Date(
            currentDate.getFullYear(),
            currentDate.getMonth(),
            1
        );
        const lastDay = new Date(
            currentDate.getFullYear(),
            currentDate.getMonth() + 1,
            0
        );
        const totalDays = lastDay.getDate();

        // Add empty cells for days before the first day of month
        const firstDayIndex = firstDay.getDay(); // 0 = Sunday, 1 = Monday, etc.
        for (let i = 0; i < firstDayIndex; i++) {
            const emptyDay = document.createElement("div");
            emptyDay.className = "calendar-day empty";
            calendarGrid.appendChild(emptyDay);
        }

        // Add days of the month
        const today = new Date();
        for (let day = 1; day <= totalDays; day++) {
            const date = new Date(
                currentDate.getFullYear(),
                currentDate.getMonth(),
                day
            );
            const dayElement = document.createElement("div");
            dayElement.className = "calendar-day";
            dayElement.textContent = day;

            // Check if this date has users
            const dateKey = formatDateKey(date);
            if (usersData[dateKey]) {
                // Add indicator for dates with users
                const indicator = document.createElement("div");
                indicator.className = "calendar-day-indicator";
                dayElement.appendChild(indicator);
            }

            // Check if this day is today
            if (
                date.getDate() === today.getDate() &&
                date.getMonth() === today.getMonth() &&
                date.getFullYear() === today.getFullYear()
            ) {
                dayElement.classList.add("today");
            }

            // Check if this day is selected
            if (
                selectedDate &&
                date.getDate() === selectedDate.getDate() &&
                date.getMonth() === selectedDate.getMonth() &&
                date.getFullYear() === selectedDate.getFullYear()
            ) {
                dayElement.classList.add("selected");
            }

            // Add click event to update users list
            dayElement.addEventListener("click", (e) => {
                e.stopPropagation();

                // If clicking the same date, just toggle selection
                if (
                    selectedDate &&
                    date.getDate() === selectedDate.getDate() &&
                    date.getMonth() === selectedDate.getMonth() &&
                    date.getFullYear() === selectedDate.getFullYear()
                ) {
                    selectedDate = null;
                    // Reset users list
                    usersWrapper.innerHTML = `
              <div class="no-selection-message">
                <i class="fas fa-calendar-day text-gray-400 text-4xl mb-3"></i>
                <p>Please select a date on the calendar to view users</p>
              </div>
            `;
                    dateDisplay.textContent = "Select a date to view users";
                    renderCalendar();
                    return;
                }

                // Update selected date
                selectedDate = date;

                // Display users for this date
                displayUsersForDate(date);

                // Update calendar to show selected date
                renderCalendar();
            });

            calendarGrid.appendChild(dayElement);
        }

        calendarContainer.appendChild(calendarGrid);
    }

    function previousMonth() {
        currentDate = new Date(
            currentDate.getFullYear(),
            currentDate.getMonth() - 1,
            1
        );
        renderCalendar();
    }

    function nextMonth() {
        currentDate = new Date(
            currentDate.getFullYear(),
            currentDate.getMonth() + 1,
            1
        );
        renderCalendar();
    }

    // Initialize calendar
    renderCalendar();
});
