document.addEventListener("DOMContentLoaded", () => {

    const slotList = document.getElementById("slot_list");
    const slotDateInput = document.getElementById("slot_date"); // input type="date"
    const hourInput = document.getElementById("slot_time");
    const addBtn = document.getElementById("add_slot");
    const errorMessage = document.getElementById("slot-error-message");
    const successMessage = document.getElementById("slot-success-message");

    // Funzione per mostrare messaggi di errore
    function showError(msg) {
        errorMessage.textContent = msg;
        errorMessage.classList.remove("d-none");
        successMessage.classList.add("d-none");
        setTimeout(() => {
            errorMessage.classList.add("d-none");
        }, 5000);
    }

    // Funzione per mostrare messaggi di successo
    function showSuccess(msg) {
        successMessage.textContent = msg;
        successMessage.classList.remove("d-none");
        errorMessage.classList.add("d-none");
        setTimeout(() => {
            successMessage.classList.add("d-none");
        }, 3000);
    }

    // Imposta la data minima a oggi
    const today = new Date().toISOString().split("T")[0];
    slotDateInput.min = today;

    // Carica gli slot quando la data cambia
    slotDateInput.addEventListener("change", () => {
        const selectedDate = slotDateInput.value;
        if (!selectedDate) return;
        loadSlots(selectedDate);
    });

    //bottone per aggiungere slot
    addBtn.addEventListener("click", (e) => {
        const date = slotDateInput.value;
        const time = hourInput.value;
        console.log(time);
        if (!date || !time) {
            return;
        }
        
        // Validazione: lo slot deve essere almeno 15 minuti dopo l'ora corrente
        const now = new Date();
        const slotDateTime = new Date(`${date}T${time}`);
        const minAllowedTime = new Date(now.getTime() + 15 * 60 * 1000);
        
        if (slotDateTime <= now) {
            showError("Non puoi aggiungere uno slot nel passato o nell'ora corrente.");
            return;
        }
        
        if (slotDateTime < minAllowedTime) {
            showError("Lo slot deve essere almeno 15 minuti dopo l'ora corrente.");
            return;
        }
        
        // FormData per POST tradizionale
        const formData = new FormData();
        formData.append("date", date);
        formData.append("hour", time);
        fetch("api/add-slot.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Ricarica la lista di slot
                loadSlots(date);
                showSuccess("Slot aggiunto con successo");
            } else {
                showError(data.error || "Impossibile aggiungere slot");
            }
        })
        .catch(err => {
            console.error("Errore nella richiesta:", err);
            showError("Errore nel server");
        });
    });

    //bottone per eliminare slot
    slotList.addEventListener("click", (e) => {
        const btn = e.target.closest(".delete-slot");
        if (!btn) return;

        const slotTime = btn.dataset.id;
        const slotDate = slotDateInput.value;

        // FormData per POST tradizionale
        const formData = new FormData();
        formData.append("date", slotDate);
        formData.append("hour", slotTime);

        fetch("api/delete-slot.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Ricarica la lista di slot
                loadSlots(slotDate);
                showSuccess("Slot eliminato con successo");
            } else {
                showError(data.error || "Impossibile eliminare slot");
            }
        })
        .catch(err => {
            console.error("Errore nella richiesta:", err);
            showError("Errore nel server");
        });
    });

});

// Funzione async per caricare gli slot
    async function loadSlots(selectedDate) {
        const slotList = document.getElementById("slot_list");
        slotList.innerHTML = `<div class="col-12 text-center text-muted">Caricamento...</div>`;

        try {
            const res = await fetch(`api/get-time-slots.php?date=${selectedDate}`);
            const data = await res.json();

            const slots = data.slots;
            if (!slots || slots.length === 0) {
                slotList.innerHTML = `<div class="col-12 text-center text-muted">Nessuno slot disponibile</div>`;
                return;
            }

            slotList.innerHTML = '<h2 class="h5 m-0">Lista slots</h2>';
            slots.forEach(slot => {
                const html = `
                <div class="col-12 col-md-6" data-id="${slot.value}">
                    <div class="card shadow-sm p-3 d-flex flex-row justify-content-between align-items-center">
                        <div>
                            <p class="mb-0"><strong>Data:</strong> ${selectedDate}</p>
                            <p class="mb-0"><strong>Orario:</strong> ${slot.label.slice(0,5)}</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-danger delete-slot" data-id="${slot.value}">
                                <i class="bi bi-trash me-1" aria-hidden="true"></i> Elimina
                            </button>
                        </div>
                    </div>
                </div>`;
                slotList.insertAdjacentHTML("beforeend", html);
            });
        } catch (err) {
            console.error("Errore nel caricamento degli slot:", err);
            slotList.innerHTML = `<div class="col-12 text-center text-danger">Errore nel caricamento slot</div>`;
        }
    }
