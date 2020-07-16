
        <div class="row row-sm">
          <div class="col-lg-4">
            <div class="card">
              <div id="rs1" class="wd-100p ht-200"></div>
              <div class="overlay-body pd-x-20 pd-t-20">
                <div class="d-flex justify-content-between">
                  <div>
                    <h6 class="tx-12 tx-uppercase tx-inverse tx-bold mg-b-5">Výdělek za dnešek</h6>
                    <p class="tx-12"><?php echo $mesice[(int)date('m', time())] . " " . date('d, Y', time()); ?></p>
                  </div>
                  <a href="" class="tx-gray-600 hover-info"><i class="icon ion-more tx-16 lh-0"></i></a>
                </div><!-- d-flex -->
                <h2 class="mg-b-5 tx-inverse tx-lato">0 Kč</h2>
                <p class="tx-12 mg-b-0">Výdělek před zdaněním.</p>
              </div>
            </div><!-- card -->
          </div><!-- col-4 -->
          <div class="col-lg-4 mg-t-15 mg-sm-t-20 mg-lg-t-0">
            <div class="card">
              <div id="rs2" class="wd-100p ht-200"></div>
              <div class="overlay-body pd-x-20 pd-t-20">
                <div class="d-flex justify-content-between">
                  <div>
                    <h6 class="tx-12 tx-uppercase tx-inverse tx-bold mg-b-5">Výdělek tento týden</h6>
                    <p class="tx-12"><?php echo $mesice[(int)date('m', time())] . " ". date('d', strtotime("this week")) . " - " . date('d, Y', strtotime("this week +6 days")); ?></p>
                  </div>
                  <a href="" class="tx-gray-600 hover-info"><i class="icon ion-more tx-16 lh-0"></i></a>
                </div><!-- d-flex -->
                <h2 class="mg-b-5 tx-inverse tx-lato">0 Kč</h2>
                <p class="tx-12 mg-b-0">Výdělek před zdaněním.</p>
              </div>
            </div><!-- card -->
          </div><!-- col-4 -->
          <div class="col-lg-4 mg-t-15 mg-sm-t-20 mg-lg-t-0">
            <div class="card">
              <div id="rs3" class="wd-100p ht-200"></div>
              <div class="overlay-body pd-x-20 pd-t-20">
                <div class="d-flex justify-content-between">
                  <div>
                    <h6 class="tx-12 tx-uppercase tx-inverse tx-bold mg-b-5">Výdělek za tento měsíc</h6>
                    <p class="tx-12"><?php echo $mesice[(int)date('m', time())] . " 1 - " . date('d, Y', strtotime("last day of this month")); ?></p>
                  </div>
                  <a href="" class="tx-gray-600 hover-info"><i class="icon ion-more tx-16 lh-0"></i></a>
                </div><!-- d-flex -->
                <h2 class="mg-b-5 tx-inverse tx-lato">0 Kč</h2>
                <p class="tx-12 mg-b-0">Výdělek před zdaněním.</p>
              </div>
            </div><!-- card -->
          </div><!-- col-4 -->
        </div><!-- row -->