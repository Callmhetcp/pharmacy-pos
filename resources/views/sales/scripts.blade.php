<script>


// ===============================
// ADD MEDICINE TO DRAFT
// ===============================

let currentDraftId = null;
let selectedMedicine = -1;

const form = document.getElementById("addMedicineForm");

function addMedicineToDraft(medicineId, quantity = 1){

    if(!currentDraftId){
        alert("Please select a draft first.");
        return;
    }

    const formData = new FormData();

    formData.append("medicine_id", medicineId);
    formData.append("quantity", quantity);

    fetch(`/drafts/${currentDraftId}/add-item`,{

        method:"POST",

        headers:{
            "X-CSRF-TOKEN":
            document.querySelector('meta[name="csrf-token"]').content,

            "X-Requested-With":"XMLHttpRequest"
        },

        body: formData

    })

    .then(response => response.json())

    .then(data => {

        updateDraftTable(data.items);

        updateDraftList(data.drafts);

        updateOrderSummary(data.items);

        // Ready for next medicine
        medicineId.value = option.dataset.id;

        medicineSearch.value = option.dataset.name;

        medicineResults.style.display = "none";

        selectedMedicine = -1;

        // Automatically add the medicine
        addMedicineToDraft(option.dataset.id);

        medicineSearch.focus();

    })

    .catch(error => console.log(error));

}


if(form){

    form.addEventListener("submit", function(e){

        e.preventDefault();


        console.log("Submit intercepted");


        let draftId = currentDraftId || this.dataset.draft;

        

        let formData = new FormData(this);



        fetch(`/drafts/${draftId}/add-item`, {

            method:"POST",

            headers:{

                "X-CSRF-TOKEN":
                document.querySelector('meta[name="csrf-token"]').content,


                "X-Requested-With":"XMLHttpRequest"

            },

            body:formData


        })


        .then(response => response.json())


        .then(data => {


            console.log("FULL RESPONSE:", data);


            console.log("DRAFT DATA:", data.drafts);



            updateDraftTable(data.items);
            updateOrderSummary(data.items);

            console.log("SENDING TO FUNCTION:", data.drafts);
            updateDraftList(data.drafts);


                    })


                    .catch(error=>console.log(error));



                });

}





// ===============================
// REMOVE ITEM FROM DRAFT
// ===============================
document.addEventListener("click", function (e) {

    const button = e.target.closest(".remove-item");

    if (!button) return;

    const itemId = button.dataset.id;

    Swal.fire({
        title: "Remove medicine?",
        text: "This item will be removed from the draft.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, remove it",
        cancelButtonText: "Cancel"
    }).then((result) => {

        if (!result.isConfirmed) return;

        fetch(`/drafts/items/${itemId}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN":
                    document.querySelector('meta[name="csrf-token"]').content,
                "X-Requested-With": "XMLHttpRequest"
            }
        })
        .then(response => response.json())
        .then(data => {

            updateDraftTable(data.items);
            updateOrderSummary(data.items);

            if (data.drafts) {
                updateDraftList(data.drafts);
            }

            Swal.fire({
                icon: "success",
                title: "Removed!",
                text: "Medicine removed from the draft.",
                timer: 1500,
                showConfirmButton: false
            });

        })
        .catch(error => {
            console.error(error);

            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Unable to remove the medicine."
            });
        });

    });

});



// ===============================
// UPDATE DRAFT LIST
// ===============================

// ===============================
// UPDATE DRAFT LIST
// ===============================

function updateDraftList(drafts){

    console.log("UPDATE DRAFT LIST RUNNING =>", drafts);

    let html = "";

    if(!drafts || drafts.length === 0){

        html = `
            <div class="text-center p-4 text-muted">
                No Drafts Available
            </div>
        `;

    }else{

        drafts.forEach(draft=>{

            let badge = "";

            switch(draft.status){

                case "open":
                    badge = `<span class="badge bg-success">Open</span>`;
                    break;

                case "held":
                    badge = `<span class="badge bg-warning text-dark">Held</span>`;
                    break;

                case "completed":
                    badge = `<span class="badge bg-primary">Completed</span>`;
                    break;

                case "cancelled":
                    badge = `<span class="badge bg-danger">Cancelled</span>`;
                    break;

                default:
                    badge = `<span class="badge bg-secondary">${draft.status}</span>`;
            }

            html += `

            <div class="list-group-item draft-item
                ${currentDraftId == draft.id ? 'active border-success border-2' : ''}"
                data-id="${draft.id}">

                <div class="d-flex justify-content-between align-items-center">

                    <div class="load-draft flex-grow-1" style="cursor:pointer">

                        <div class="fw-bold">
                            ${draft.draft_number}
                        </div>

                        <small class="text-muted d-block">
                            <i class="fas fa-user me-1"></i>
                            ${draft.customer ? draft.customer.name : "Walk-in Customer"}
                        </small>

                        <small class="text-muted d-block">
                            <i class="fas fa-shopping-basket me-1"></i>
                            ${draft.items ? draft.items.length : 0} Item(s)
                        </small>

                    </div>

                    <div class="text-end">

                        ${badge}

                        <br>

                        <button
                            class="btn btn-sm btn-outline-danger mt-2 delete-draft"
                            data-id="${draft.id}">

                            <i class="fas fa-trash"></i>

                        </button>

                    </div>

                </div>

            </div>

            `;

        });

    }

    document.getElementById("draftList").innerHTML = html;

}






// ===============================
// UPDATE CART TABLE
// ===============================


function updateDraftTable(items){



    let html="";



    if(items.length === 0){


        html = `


        <tr>

            <td colspan="5" class="text-center py-5">

                Cart is Empty

            </td>

        </tr>


        `;



    }


    else{



        items.forEach(item=>{


            html += `


            <tr>


                <td>

                    ${item.medicine.name}

                </td>


                <td class="text-center">

                    <div class="btn-group btn-group-sm">

                        <button
                            class="btn btn-outline-secondary decrease-qty"
                            data-id="${item.id}">
                            <i class="fas fa-minus"></i>
                        </button>

                        <button
                            class="btn btn-light"
                            disabled>
                            ${item.quantity}
                        </button>

                        <button
                            class="btn btn-outline-success increase-qty"
                            data-id="${item.id}">
                            <i class="fas fa-plus"></i>
                        </button>

                    </div>

                </td>


                <td>

                    ₦${parseFloat(item.unit_price).toFixed(2)}

                </td>


                <td>

                    ₦${parseFloat(item.subtotal).toFixed(2)}

                </td>


                <td class="text-center">


                    <button

                        class="btn btn-outline-danger btn-sm remove-item"

                        data-id="${item.id}">


                        <i class="fas fa-trash"></i>


                    </button>


                </td>


            </tr>


            `;


        });



    }



    document.getElementById("cartTable").innerHTML = html;



}
// ===============================
// UPDATE ORDER SUMMARY
// ===============================

function updateOrderSummary(items) {

    let totalItems = items.length;
    let totalQuantity = 0;
    let subtotal = 0;

    items.forEach(item => {

        totalQuantity += Number(item.quantity);
        subtotal += Number(item.subtotal);

    });

    // VAT Percentage from Settings
    const vatRate = {{ $setting->tax ?? 0 }};

    // Discount (for now)
    let discount = 0;

    // VAT Amount
    let tax = ((subtotal - discount) * vatRate) / 100;

    // Grand Total
    let grandTotal = (subtotal - discount) + tax;

    // Update Order Summary
    document.getElementById("totalItems").textContent = totalItems;

    document.getElementById("totalQuantity").textContent = totalQuantity;

    document.getElementById("subTotal").textContent =
        subtotal.toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

    document.getElementById("discount").textContent =
        discount.toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

    document.getElementById("tax").textContent =
        tax.toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

    document.getElementById("grandTotal").textContent =
        "₦" + grandTotal.toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

    // Refresh payment calculation
    if (window.calculatePayment) {
        window.calculatePayment();
    }

}





// ===============================
// PAYMENT CALCULATION
// ===============================


document.addEventListener("DOMContentLoaded", function(){



    const amountPaid = document.getElementById("amount_paid");


    const completeSale = document.getElementById("completeSale");


    const change = document.getElementById("change");


    const grandTotal = document.getElementById("grandTotal");




    function calculateChange(){



        if(!amountPaid || !completeSale || !change || !grandTotal){

            return;

        }




        let total = parseFloat(

            grandTotal.innerText.replace(/[₦,]/g,"")

        ) || 0;




        let paid = parseFloat(amountPaid.value) || 0;




        let balance = paid - total;




        if(paid >= total && total > 0){



            completeSale.disabled = false;



            change.value =

            "₦" + balance.toLocaleString(undefined,{

                minimumFractionDigits:2,

                maximumFractionDigits:2

            });



        }

        else{



            completeSale.disabled = true;


            change.value = "₦0.00";

        }



    }




    if(amountPaid){

        amountPaid.addEventListener("input", calculateChange);

    }



    calculateChange();



});

// ===============================
// LOAD SELECTED DRAFT
// ===============================


document.addEventListener("click", function(e){


    const draftButton = e.target.closest(".load-draft");


    if(!draftButton) return;



    const draftBox = draftButton.closest(".draft-item");


    let draftId = draftBox.dataset.id;



    currentDraftId = draftId;

    document.getElementById("checkoutDraftId").value = currentDraftId;

    



    console.log("CURRENT DRAFT:", currentDraftId);
    // ===============================
// HIGHLIGHT ACTIVE DRAFT
// ===============================

    document.querySelectorAll(".draft-item")
    .forEach(item => {

        item.classList.remove("active");

    });


    draftBox.classList.add("active");


    fetch(`/drafts/${draftId}`)


    .then(response => response.json())


    .then(data => {


        console.log("LOADED DRAFT:", data);



         updateDraftTable(data.items);
        updateOrderSummary(data.items);



    })


    .catch(error=>console.log(error));


});

// ===============================
// CREATE NEW DRAFT
// ===============================

// ===============================
// CREATE NEW DRAFT
// ===============================

document.addEventListener("click", function(e){


    const newDraftButton = e.target.closest("#newDraft");


    if(!newDraftButton) return;


    console.log("NEW DRAFT BUTTON CLICKED");


    fetch("/drafts/new", {

        method:"POST",

        headers:{

            "X-CSRF-TOKEN":
            document.querySelector('meta[name="csrf-token"]').content,

            "X-Requested-With":"XMLHttpRequest",

            "Accept":"application/json"

        }

    })


    .then(response => response.json())


    .then(data => {


        console.log("NEW DRAFT CREATED:", data);



        // update draft list immediately

        if(data.drafts){

            updateDraftList(data.drafts);

        }

        else{

            // fallback: reload only the draft list

            fetch("/drafts/list")

            .then(response => response.text())

            .then(html => {

                document.getElementById("draftList").innerHTML = html;

            });

        }



        // make the new draft active

        if(data.draft){

            currentDraftId = data.draft.id;

            document.getElementById("checkoutDraftId").value = data.draft.id;

            console.log("ACTIVE NEW DRAFT:", currentDraftId);


            // clear cart area

            updateDraftTable([]);
            updateOrderSummary([]);

        }


    })


    .catch(error => console.log(error));
    


});

// ===============================
// UPDATE CUSTOMER FOR ACTIVE DRAFT
// ===============================

const customerSelect = document.getElementById("customerSelect");


if(customerSelect){


    customerSelect.addEventListener("change", function(){


        let customerId = this.value;



        if(!currentDraftId){


            alert("Please select a draft first");


            this.value = "";


            return;


        }



        console.log(
            "UPDATING CUSTOMER:",
            customerId,
            "FOR DRAFT:",
            currentDraftId
        );



        fetch(`/drafts/${currentDraftId}/customer`, {


            method:"POST",


            headers:{


                "X-CSRF-TOKEN":
                document.querySelector('meta[name="csrf-token"]').content,


                "X-Requested-With":"XMLHttpRequest",


                "Accept":"application/json",


                "Content-Type":"application/json"


            },


            body:JSON.stringify({


                customer_id: customerId


            })


        })


        .then(response=>response.json())


        .then(data=>{


            console.log("CUSTOMER UPDATED SUCCESSFULLY:", data);



            if(data.success){


                console.log(
                    "Draft",
                    currentDraftId,
                    "now belongs to customer",
                    customerId
                );


            }


        })


        .catch(error=>{


            console.log(
                "CUSTOMER UPDATE ERROR:",
                error
            );


        });



    });


}

// ===============================
// DELETE DRAFT
// ===============================

document.addEventListener("click", function(e){

    const deleteButton = e.target.closest(".delete-draft");

    if(!deleteButton) return;

    const draftId = deleteButton.dataset.id;

    const draftBox = deleteButton.closest(".draft-item");

    const itemCount = parseInt(
        draftBox.querySelector("small").textContent
    );

    if(itemCount > 0){

        if(!confirm("This draft contains items.\n\nDelete it anyway?")){
            return;
        }

    }

    fetch(`/drafts/${draftId}`,{

        method:"DELETE",

        headers:{

            "X-CSRF-TOKEN":
            document.querySelector('meta[name="csrf-token"]').content,

            "X-Requested-With":"XMLHttpRequest"

        }

    })

    .then(response=>response.json())

    .then(data=>{

        if(!data.success) return;

        // Refresh draft list
        updateDraftList(data.drafts);

        // If there are drafts left, automatically load the first one
        if(data.drafts.length > 0){

            const firstDraft = data.drafts[0];

            currentDraftId = firstDraft.id;

            document.getElementById("checkoutDraftId").value = firstDraft.id;

            fetch(`/drafts/${firstDraft.id}`)

            .then(response=>response.json())

            .then(result=>{

            updateDraftTable(result.items);
            updateOrderSummary(result.items);
            });

        }

        // If no drafts remain, create one automatically
        else{

            document.getElementById("newDraft").click();

        }

    })

    .catch(error=>console.log(error));

});

// ===============================
// UPDATE ITEM QUANTITY (+ / -)
// ===============================

document.addEventListener("click", function(e){

    const increaseBtn = e.target.closest(".increase-qty");
    const decreaseBtn = e.target.closest(".decrease-qty");

    if(!increaseBtn && !decreaseBtn) return;

    const button = increaseBtn || decreaseBtn;

    const itemId = button.dataset.id;

    const action = increaseBtn ? "increase" : "decrease";

    fetch(`/drafts/items/${itemId}/quantity`,{

        method:"PATCH",

        headers:{

            "X-CSRF-TOKEN":
            document.querySelector('meta[name="csrf-token"]').content,

            "X-Requested-With":"XMLHttpRequest",

            "Content-Type":"application/json"

        },

        body: JSON.stringify({

            action: action

        })

    })

    .then(response => response.json())

    .then(data => {

        if(!data.success){

            alert(data.message);

            return;

        }

        updateDraftTable(data.items);

        updateOrderSummary(data.items);

        if(data.drafts){

            updateDraftList(data.drafts);

        }

    })

    .catch(error => console.log(error));

});

// ===============================
// MEDICINE SEARCH
// ===============================

const medicineSearch = document.getElementById("medicineSearch");
const medicineResults = document.getElementById("medicineResults");
const medicineId = document.getElementById("medicine_id");


// Load medicines
function loadMedicines(search = "") {

    if (!medicineSearch || !medicineResults) {
        return;
    }

    fetch(`/medicines/search?search=${encodeURIComponent(search)}`)

        .then(response => {

            if (!response.ok) {
                throw new Error("Failed to load medicines");
            }

            return response.json();

        })

        .then(data => {

            let html = "";

            if (data.length === 0) {

                html = `
                    <div class="list-group-item text-muted text-center py-3">

                        <i class="fas fa-search me-2"></i>

                        No medicine found

                    </div>
                `;

            } else {

                data.forEach(medicine => {

                    html += `

                        <a href="#"
                           class="list-group-item list-group-item-action medicine-option"
                           data-id="${medicine.id}"
                           data-name="${medicine.name}">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <strong>
                                        ${medicine.name}
                                    </strong>

                                    <small class="d-block text-muted">

                                        ₦${parseFloat(
                                            medicine.selling_price || 0
                                        ).toLocaleString()}

                                    </small>

                                </div>


                                <span class="badge bg-success">

                                    ${medicine.quantity ?? 0} in stock

                                </span>

                            </div>

                        </a>

                    `;

                });

            }

            medicineResults.innerHTML = html;

            medicineResults.style.display = "block";

            selectedMedicine = -1;

        })

        .catch(error => {

            console.error("Medicine search error:", error);

            medicineResults.innerHTML = `

                <div class="list-group-item text-danger text-center">

                    Unable to load medicines.

                </div>

            `;

            medicineResults.style.display = "block";

        });
}


// ===============================
// SHOW ALL MEDICINES ON FOCUS
// ===============================

if (medicineSearch) {

    medicineSearch.addEventListener("focus", function () {

        loadMedicines(this.value.trim());

    });


    // ===============================
    // FILTER WHILE TYPING
    // ===============================

    let medicineSearchTimeout;

    medicineSearch.addEventListener("input", function () {

        const search = this.value.trim();

        medicineId.value = "";

        clearTimeout(medicineSearchTimeout);

        medicineSearchTimeout = setTimeout(function () {

            loadMedicines(search);

        }, 300);

    });

}
// ===============================
// KEYBOARD NAVIGATION
// ===============================

if (medicineSearch) {

    medicineSearch.addEventListener("keydown", function(e) {

        const results = document.querySelectorAll(".medicine-option");

        if (results.length === 0) return;


        // ===============================
        // ARROW DOWN
        // ===============================

        if (e.key === "ArrowDown") {

            e.preventDefault();

            selectedMedicine++;

            if (selectedMedicine >= results.length) {

                selectedMedicine = 0;

            }

            highlightMedicine(results);

        }


        // ===============================
        // ARROW UP
        // ===============================

        if (e.key === "ArrowUp") {

            e.preventDefault();

            selectedMedicine--;

            if (selectedMedicine < 0) {

                selectedMedicine = results.length - 1;

            }

            highlightMedicine(results);

        }


        // ===============================
        // ENTER
        // ===============================

        if (e.key === "Enter") {

            e.preventDefault();

            if (selectedMedicine >= 0) {

                results[selectedMedicine].click();

            }

        }

    });

}


// ===============================
// HIGHLIGHT SELECTED MEDICINE
// ===============================

function highlightMedicine(results) {

    results.forEach(item => {

        item.classList.remove("active");

    });


    if (
        selectedMedicine >= 0 &&
        selectedMedicine < results.length
    ) {

        results[selectedMedicine].classList.add("active");

        results[selectedMedicine].scrollIntoView({

            block: "nearest"

        });

    }

}

// ===============================
// SELECT MEDICINE
// ===============================

document.addEventListener("click", function(e){

    const option = e.target.closest(".medicine-option");

    if(!option) return;

    e.preventDefault();

    medicineId.value = option.dataset.id;

    medicineSearch.value = option.dataset.name;

    medicineResults.style.display = "none";

    selectedMedicine = -1;

});

// Hide search list when clicking outside
document.addEventListener("click", function(e){

    if(!e.target.closest("#medicineSearch") &&
       !e.target.closest("#medicineResults")){

        medicineResults.style.display = "none";

    }

});

// ===============================
// CLEAR DRAFT
// ===============================
document.addEventListener("click", function (e) {

    const button = e.target.closest("#clearDraft");

    if (!button) return;

    if (!currentDraftId) {

        Swal.fire({
            icon: "warning",
            title: "No Draft Selected",
            text: "Please select a draft first."
        });

        return;
    }

    Swal.fire({
        title: "Clear Draft?",
        text: "This will remove all medicines from this draft.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, Clear It",
        cancelButtonText: "Cancel"
    }).then((result) => {

        if (!result.isConfirmed) return;

        fetch(`/drafts/${currentDraftId}/clear`, {

            method: "DELETE",

            headers: {

                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,

                "X-Requested-With": "XMLHttpRequest"

            }

        })

        .then(response => response.json())

        .then(data => {

            updateDraftTable([]);
            updateDraftList(data.drafts);
            updateOrderSummary([]);

            Swal.fire({
                icon: "success",
                title: "Cleared!",
                text: "Draft cleared successfully.",
                timer: 1500,
                showConfirmButton: false
            });

        })

        .catch(error => {
            console.log(error);

            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Unable to clear draft."
            });
        });

    });

});


// ===============================
// HOLD DRAFT
// ===============================
document.addEventListener("click", function (e) {

    const button = e.target.closest("#holdDraft");

    if (!button) return;

    if (!currentDraftId) {

        Swal.fire({
            icon: "warning",
            title: "No Draft Selected",
            text: "Please select a draft first."
        });

        return;
    }

    fetch(`/drafts/${currentDraftId}/hold`, {

        method: "PATCH",

        headers: {

            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,

            "X-Requested-With": "XMLHttpRequest"

        }

    })

    .then(response => response.json())

    .then(data => {

        console.log(data);

        updateDraftList(data.drafts);

        Swal.fire({
            icon: "success",
            title: "Draft Held",
            text: "The draft has been placed on hold.",
            timer: 1500,
            showConfirmButton: false
        });

        if (data.active) {

            currentDraftId = data.active.id;

            document.getElementById("checkoutDraftId").value = currentDraftId;

            fetch(`/drafts/${currentDraftId}`)

            .then(response => response.json())

            .then(draftData => {

                updateDraftTable(draftData.items);

                updateOrderSummary(draftData.items);

                if (draftData.draft.customer_id) {

                    document.getElementById("customerSelect").value = draftData.draft.customer_id;

                } else {

                    document.getElementById("customerSelect").value = "";

                }

            });

        }

    })

    .catch(error => {

        console.log(error);

        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Unable to hold draft."
        });

    });

});
</script>