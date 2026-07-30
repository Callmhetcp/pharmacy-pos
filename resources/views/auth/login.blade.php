<x-guest-layout>


<form method="POST" action="{{ route('login') }}">

    @csrf



    <!-- EMAIL -->

    <div class="mb-3 text-start">


        <label class="form-label fw-semibold">

            Email Address

        </label>


        <div class="input-group-custom">


            <i class="fas fa-envelope"></i>


            <input

                type="email"

                name="email"

                value="{{ old('email') }}"

                class="form-control"

                placeholder="Enter your email"

                required

                autofocus

            >


        </div>


        @error('email')

            <small class="text-danger">

                {{ $message }}

            </small>

        @enderror


    </div>





    <!-- PASSWORD -->

    <div class="mb-3 text-start">


        <label class="form-label fw-semibold">

            Password

        </label>



        <div class="input-group-custom">


            <i class="fas fa-lock"></i>


            <input

                type="password"

                name="password"

                id="password"

                class="form-control"

                placeholder="Enter your password"

                required

            >


        </div>



        @error('password')

            <small class="text-danger">

                {{ $message }}

            </small>

        @enderror



    </div>






    <!-- REMEMBER + FORGOT -->


    <div class="d-flex justify-content-between align-items-center mb-4">


        <div class="form-check">


            <input

                class="form-check-input"

                type="checkbox"

                name="remember"

                id="remember_me"



            >


            <label

                class="form-check-label small"

                for="remember_me">

                Remember me

            </label>


        </div>





        @if (Route::has('password.request'))


        <a class="auth-link small"

           href="{{ route('password.request') }}">

            Forgot Password?

        </a>


        @endif



    </div>







    <!-- LOGIN BUTTON -->


    <button

        type="submit"

        class="auth-btn">


        <i class="fas fa-right-to-bracket me-2"></i>


        Login To Dashboard


    </button>






</form>






<!-- FOOTER -->


<div class="mt-4">


    <small class="text-muted">


        Secure Pharmacy Management System


        <br>


        <i class="fas fa-shield-halved text-success mt-2"></i>

        Your data is protected


    </small>


</div>



</x-guest-layout>