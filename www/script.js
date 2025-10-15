document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("myForm");
  // const result = document.getElementById("result");
  const clearBtn = document.getElementById("clearBtn");

  let lastInputAt = Date.now();
  let reminderShown = false;

  form.addEventListener("input", () => {
    lastInputAt = Date.now();
    reminderShown = false;
  });

  setInterval(() => {
    const inactive = Date.now() - lastInputAt > 15000;

    if (inactive && !reminderShown) {
      const isEmpty =
        !form.name.value &&
        !form.age.value &&
        !form.track.value;

      if (isEmpty) {
        alert("Пожалуйста, заполните форму!");
      } else {
        highlightFields(form);
      }

      reminderShown = true;
    }
  }, 3000);

  form.addEventListener("submit", (e) => {
    //e.preventDefault();
    const fd = new FormData(form);

    //Собираем данные для alert
    let alertText = "Данные формы:\n";
    for (const [name, value] of fd.entries()) {
      alertText += `${name}: ${value}\n`;
    }
    alert(alertText);

    //result.innerHTML = alertText;
  });

  clearBtn.addEventListener("click", () => {
    form.reset();
    result.innerHTML = "Форма очищена.";
    lastInputAt = Date.now();
    reminderShown = false;
  });

  function highlightFields(form) {
    form.querySelectorAll("input, select").forEach((el) => {
      el.style.boxShadow = "0 0 0 3px rgba(255,220,0,0.6)";
      setTimeout(() => (el.style.boxShadow = ""), 1200);
    });
  }
});
