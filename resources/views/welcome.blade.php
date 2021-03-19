@extends('layouts.frontend.whole')
@section('content')
     
<section class="section hero-section bg-ico-hero" id="home">
    <div class="bg-overlay bg-primary"></div>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5">
                <div class="text-white-50">
                    <h1 class="text-white font-weight-semibold mb-3 hero-title" style="font-size: 60px;">OneLab <span class="icofont icofont-test-tube-alt" style="font-size: 50px;"></span></h1> 
                    <p class="font-size-14">One-stop Laboratory Services for Global Competitiveness</p>
                    
                    <div class="button-items mt-4">
                        <a href="#" class="btn btn-success">Get Whitepaper</a>
                        <a href="#" class="btn btn-light">How it work</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-md-8 col-sm-10 ml-lg-auto">
                <div>
                    <img src="{{asset('/assets/backend/images/crypto/features-img/img-1.png')}}" alt="" class="img-fluid mx-auto d-block">
                </div>
            </div>
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</section>

<section class="section bg-white p-0">
    <div class="container">
        <div class="currency-price">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="media">
                                <div class="avatar-xs mr-3">
                                    <span class="avatar-title rounded-circle bg-soft-warning text-warning font-size-18">
                                        <i class="mdi mdi-earth"></i>
                                    </span>
                                </div>
                                <div class="media-body">
                                    <p class="text-muted">International Laboratories</p>
                                    <h5>$ 9134.39</h5>
                                    <p class="text-muted text-truncate mb-0">+ 0.0012.23 ( 0.2 % ) <i class="mdi mdi-arrow-up ml-1 text-success"></i></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="media">
                                <div class="avatar-xs mr-3">
                                    <span class="avatar-title rounded-circle bg-soft-primary text-danger font-size-18">
                                        <i class="mdi mdi-beaker"></i>
                                    </span>
                                </div>
                                <div class="media-body">
                                    <p class="text-muted">Non-Dost Laboratories</p>
                                    <h5>$ 245.44</h5>
                                    <p class="text-muted text-truncate mb-0">- 004.12 ( 0.1 % ) <i class="mdi mdi-arrow-down ml-1 text-danger"></i></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="media">
                                <div class="avatar-xs mr-3">
                                    <span class="avatar-title rounded-circle bg-soft-info text-info font-size-18">
                                        <i class="mdi mdi-flask"></i>
                                    </span>
                                </div>
                                <div class="media-body">
                                    <p class="text-muted">DOST Laboratories</p>
                                    <h5>$ 63.61</h5>
                                    <p class="text-muted text-truncate mb-0">+ 0.0001.12 ( 0.1 % ) <i class="mdi mdi-arrow-up ml-1 text-success"></i></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div>
    </div>
    <!-- end container -->
</section>

<section class="section pt-4" id="about">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center mb-5">
                    <div class="small-title">About us</div>
                    <h4>What is OneLab?</h4>
                </div>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-lg-12">

                <div class="text-muted">
                    <h4 class="mb-3">One-stop Laboratory <small> Services for Global Competitiveness</small></h4>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A Network of laboratories aimed at ensuring availability and broadening public access to testing and calibration services at a single touchpoint through an IT-based platform. OneLab uniquely facilitates the seamless laboratory transaction from sample receipt to release of test result as facilitated by the OneLab Referral System. With this efficient system, you get the fastest turn-around time and expanded test offerings in the market. Provide relevant and efficient laboratory services for industries, research institutions, other government & Non-government sectors and general public. Offer wider scope of services for different materials and products. Currently, OneLab has 16 DOST Regional Standard and Testing Laboratories(RSTL), 6 DOST Research and Development Institute(RDI) laboratories, 4 government agencies and 19 private local laboratories and 8 international laboratories as members.</p>
                    <p class="mb-4">For full details please click the Our Network section.</p>

                    {{-- <div class="button-items">
                        <a href="#" class="btn btn-success">Read More</a>
                        <a href="#" class="btn btn-outline-primary">How It work</a>
                    </div> --}}
                </div>
            </div>

            <div class="col-md-6 ml-auto">
                <div class="mt-4 mt-md-auto">
                    <div class="d-flex align-items-center mb-2">
                        <div class="features-number font-weight-semibold display-4 mr-3"><span class="icofont icofont-test-tube-alt"></span></div>
                        <h4 class="mb-0">Mission</h4>
                    </div>
                    <p class="text-muted">To provide global access to comprehensive analytical and calibration services at a single touch point.</p>
                </div>
            </div>
            <div class="col-md-6 ml-auto">
                <div class="mt-4 mt-md-auto">
                    <div class="d-flex align-items-center mb-2">
                        <div class="features-number font-weight-semibold display-4 mr-3"><span class="icofont icofont-test-bulb"></span></div>
                        <h4 class="mb-0">Vision</h4>
                    </div>
                    <p class="text-muted">To be globally-recognized network of analytical and calibration laboratories distinguished for quality, proficient services and ultimate customer convenience. </p>
                </div>
            </div>
        </div>
        <!-- end row -->

       
    </div>
    <!-- end container -->
</section>

<section class="section bg-white" id="news">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center mb-5">
                    <div class="small-title">Blog</div>
                    <h4>Latest News</h4>
                </div>
            </div>
        </div>

        <div class="row">
            @forelse($articles as $article)
            <div class="col-xl-4 col-sm-6">
                <div class="blog-box mb-4 mb-xl-0">
                    <div class="position-relative">
                        <img src="{{ URL::to('/') }}/images/articles/{{$article->image}}" alt="" class="rounded img-fluid mx-auto d-block">
                        <div class="badge badge-success blog-badge font-size-11">{{ $article->user->profile->firstname }} {{ $article->user->profile->lastname }}</div>
                    </div>

                    <div class="mt-4 text-muted">
                        <p class="mb-2"><i class="bx bx-calendar mr-1"></i> {{ $article->created_at }}</p>
                        <h5 class="mb-3">{{  \Illuminate\Support\Str::limit($article->title,30,' ...') }}</h5>
                        <p>{{  \Illuminate\Support\Str::limit($article->content,90,'...') }}</p>

                        <div>
                            <a href="#">Read more</a>
                        </div>
                    </div>
                </div>
            </div>
            @empty 
            @endforelse

        </div>
    </div>
</section>

<section class="section" id="faqs">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center mb-5">
                    <div class="small-title">FAQs</div>
                    <h4>Do you want to be a Member?</h4>
                </div>
            </div>
        </div>
        <!-- end row -->

        <div class="row">
            <div class="col-lg-12">
                <div class="vertical-nav">
                    <div class="row">
                        <div class="col-lg-2 col-sm-4">
                            <div class="nav flex-column nav-pills" role="tablist">
                                <a class="nav-link active" id="v-pills-gen-ques-tab" data-toggle="pill" href="#v-pills-gen-ques" role="tab">
                                    <i class= "bx bx-help-circle nav-icon d-block mb-2"></i>
                                    <p class="font-weight-bold mb-0">Benefits</p>
                                </a>
                                <a class="nav-link" id="v-pills-token-sale-tab" data-toggle="pill" href="#v-pills-token-sale" role="tab"> 
                                    <i class= "bx bx-receipt nav-icon d-block mb-2"></i>
                                    <p class="font-weight-bold mb-0">Requirements</p>
                                </a>
                                <a class="nav-link" id="v-pills-roadmap-tab" data-toggle="pill" href="#v-pills-roadmap" role="tab">
                                    <i class= "icofont icofont-hard-disk d-block nav-icon mb-2"></i>
                                    <p class="font-weight-bold mb-0">IT System</p>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-10 col-sm-8">
                            <div class="card" style="min-height: 330px;">
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="v-pills-gen-ques" role="tabpanel">
                                            <h4 class="card-title mb-4">Customer Benefits</h4>
                                            
                                            <div>
                                                <div id="gen-ques-accordion" class="accordion custom-accordion">
                                                    <div class="mb-3">
                                                        <a class="accordion-list">
                                                            <div>Free advertisement of Services.</div>
                                                        </a>
                                                    </div>
                                                    <div class="mb-3">
                                                        <a class="accordion-list">
                                                            <div>Networking and learning from other Member laboratories.</div>
                                                        </a>
                                                    </div>
                                                    <div class="mb-3">
                                                        <a class="accordion-list">
                                                            <div>Opportunity for benchmarking.</div>
                                                        </a>
                                                    </div>
                                                    <div class="mb-3">
                                                        <a class="accordion-list">
                                                            <div>Enhancement of methods and competence.</div>
                                                        </a>
                                                    </div>
                                                    <div class="mb-3">
                                                        <a class="accordion-list">
                                                            <div>Glimpse of the business environment needs.</div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="v-pills-token-sale" role="tabpanel">
                                            <h4 class="card-title mb-5">Member Requirements</h4>
                                            <div>
                                                <div id="token-accordion" class="accordion custom-accordion">
                                                    <div class="mb-3">
                                                        <a class="accordion-list">
                                                            <div>The laboratory should be an ISO/IEC 17025:2017 “General Requirements for the Competence of Testing and Calibration Laboratories” accredited. </div>
                                                        </a>
                                                    </div>

                                                    <div class="mb-3">
                                                        <a class="accordion-list">
                                                            <div> Membership in the network is valid for five (5) years </div>
                                                        </a>
                                                    </div>
    
                                                    <div class="mb-3">
                                                        <a class="accordion-list">
                                                            <div>Membership can be renewed upon submission of a Letter of Intent addressed to OneLab Project Leaders. </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="v-pills-roadmap" role="tabpanel">
                                            <h4 class="card-title mb-5">IT System and Hardware Requirements </h4>
                                                
                                            <div>
                                                <div id="roadmap-accordion" class="accordion custom-accordion">

                                                    <div class="mb-3">
                                                        <a class="accordion-list">
                                                            <div>The member-laboratories are required to adopt the Referral System Module of OneLab.</div>
                                                        </a>
                                                    </div>
                                                    <div class="mb-3">
                                                        <a class="accordion-list">
                                                            <div>The DOST IX-developed Enhanced Unified Laboratory Information System (eULIMS) may be used by any member free of charge. </div>
                                                        </a>
                                                    </div>
                                                    <div class="mb-3">
                                                        <a class="accordion-list">
                                                            <div>Member-laboratories having their own laboratory information system are not be obliged to use or migrate to the OneLab eULIMS. </div>
                                                        </a>
                                                    </div>

                                                    
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end vertical nav -->
            </div>
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</section>
       
@endsection
