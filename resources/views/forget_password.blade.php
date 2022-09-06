@include('nav')

<h3>Forget Password</h3>


<form action="" method="POST">
    

<div>Email Address:</div>

<div>
    <input type="text" name="email" >
</div>





<div style="margin-top: 10px;">
    <input type="submit" value="Submit">
    <br>
    <a href="{{ route('login') }}">
        Back to login page
    </a>
</div>

</form>