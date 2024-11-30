const optionMenus = document.querySelectorAll(".select-menu");

optionMenus.forEach(optionMenu => {
    const selectBtn = optionMenu.querySelector(".select-btn");
    const options = optionMenu.querySelectorAll(".option");
    const sBtnText = optionMenu.querySelector(".sBtn-text");

    // Toggle dropdown on button click
    selectBtn.addEventListener("click", (e) => {
        e.stopPropagation();  // Prevent triggering document click listener
        closeOtherMenus(optionMenu);  // Close any open menus
        optionMenu.classList.toggle("active");  // Toggle current menu
    });

    // Update selected option text and close dropdown
    options.forEach(option => {
        option.addEventListener("click", () => {
            const selectedOption = option.querySelector(".option-text").innerText;
            sBtnText.innerText = selectedOption;
            optionMenu.classList.remove("active");  // Close menu after selection
        });
    });
});

// Close menus when clicking outside
document.addEventListener("click", () => {
    optionMenus.forEach(menu => menu.classList.remove("active"));
});

// Close other open menus
function closeOtherMenus(currentMenu) {
    optionMenus.forEach(menu => {
        if (menu !== currentMenu) {
            menu.classList.remove("active");
        }
    });
}
