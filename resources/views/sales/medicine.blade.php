<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-primary text-white">

        <h5 class="mb-0">
            <i class="fas fa-pills me-2"></i>
            Add Medicine
        </h5>

    </div>


    <div class="card-body">


        <form id="addMedicineForm"
                data-draft="{{ $currentDraft->id }}">
                @csrf


            <div class="row">


                <div class="col-md-6">


                    <label class="form-label fw-bold">
                        Medicine
                    </label>


                <div class="position-relative">

                    <input
                        type="text"
                        id="medicineSearch"
                        class="form-control form-control-lg"
                        placeholder="Search medicine...">

                    <input
                        type="hidden"
                        id="medicine_id"
                        name="medicine_id">

                    <div
                        id="medicineResults"
                        class="list-group position-absolute w-100 shadow"
                        style="
                            z-index:9999;
                            display:none;
                            max-height:250px;
                            overflow:auto;
                        ">
                    </div>

                </div>


                </div>



                <div class="col-md-3">


                    <label class="form-label fw-bold">
                        Quantity
                    </label>


                    <input
                        type="number"
                        name="quantity"
                        value="1"
                        min="1"
                        class="form-control form-control-lg"
                        required>


                </div>



                <div class="col-md-3 d-flex align-items-end">


                    <button
                        type="submit"
                        class="btn btn-primary btn-lg w-100"
                        id="addMedicineBtn">


                        <i class="fas fa-cart-plus me-2"></i>

                        Add To Draft


                    </button>


                </div>


            </div>


        </form>


    </div>

</div>
