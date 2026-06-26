<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include('header.php');
//echo sha1('Mohona@123456');
?>
<main class="main" id="top">
      <div class="container-fluid bg-body-tertiary dark__bg-gray-1200">
        <div class="bg-holder bg-auth-card-overlay" style="background-image:url(assets/img/37.png);"></div>
        <!--/.bg-holder-->
        <div class="row flex-center position-relative min-vh-100 g-0 py-5">
          <div class="col-11 col-sm-10 col-xl-8">
            <div class="card border border-translucent auth-card">
              <div class="card-body pe-md-0">
                <div class="row align-items-center gx-0 gy-7">
                  <div class="col-auto bg-body-highlight dark__bg-gray-1100 rounded-3 position-relative overflow-hidden auth-title-box">
                    <div class="bg-holder" style="background-image:url(assets/img/38.png);"></div>
                    <!--/.bg-holder-->
                    <div class="position-relative px-4 px-lg-7 pt-7 pb-7 pb-sm-5 text-center text-md-start pb-lg-7 pb-md-7">
                      <h3 class="mb-3 text-body-emphasis fs-7">Sanpurple Employee Portal </h3>
                      <p class="text-body-tertiary">Works diligently to provide tailor-made strategies and solutions for clients and establish them as a reputed brand in online businesses.</p>
                     
                    </div>
                    <div class="position-relative z-n1 mb-6 d-none d-md-block text-center mt-md-15"><img class="auth-title-box-img d-dark-none" src="assets/img/auth.png" alt="" /><img class="auth-title-box-img d-light-none" src="assets/img/auth-dark.png" alt="" /></div>
                  </div>
                  <div class="col mx-auto">
                    <div class="auth-form-box">
                      <div class="text-center mb-7"><a class="d-flex flex-center text-decoration-none mb-4" href="#">
                          
                        </a>
                        <h3 class="text-body-highlight">Sign In</h3>
                        <p class="text-body-tertiary">Get access to your account</p>
                      </div>
                      <p class="text-danger text-center" id="msg"></p>
                      <form method="post" id="submit_login">
                      <div class="mb-3 text-start"><label class="form-label" for="emp_num">Employee Number</label>
                        <div class="form-icon-container"><input class="form-control form-icon-input" name="emp_num" id="emp_num" type="number"  /><span class="fas fa-user text-body fs-9 form-icon"></span></div>
                      </div>
                      <div class="mb-3 text-start"><label class="form-label" for="password">Password</label>
                        <div class="form-icon-container"><input class="form-control form-icon-input"  name="user_code"  id="password" type="password" placeholder="Password" /><span class="fas fa-key text-body fs-9 form-icon"></span></div>
                      </div>
                      <div class="row flex-between-center mb-7">
                        <div class="col-auto">
                          <div class="form-check mb-0"><input class="form-check-input" id="basic-checkbox" type="checkbox" checked="checked" /><label class="form-check-label mb-0" for="basic-checkbox">Remember me</label></div>
                        </div>
                        <div class="col-auto"><a class="fs-9 fw-semibold" href="#">Forgot Password?</a></div>
                      </div><button type="submit" class="btn btn-primary w-100 mb-3">Sign In</button>
                     </form>
                      <div class="text-center"><a class="fs-9 fw-bold" href="#">Create an account</a></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
     
    </main>

<?php include('footer.php');?>