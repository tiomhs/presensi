<div>
    <div class="card card-flush h-xl-100">
		<!--begin::Body-->
		<div class="card-body py-9">
			<!--begin::Row-->
			<div class="row gx-9 h-100">
				<!--begin::Col-->
				<div class="col-sm-6 mb-10 mb-sm-0">
					<!--begin::Overlay-->
					<a class="d-block overlay h-100" data-fslightbox="lightbox-hot-sales" href="assets/media/stock/600x600/img-42.jpg">
						<!--begin::Image-->
						<img src="{{ $dataUri }}" alt="">
						<!--end::Action-->
					</a>
					<!--end::Overlay-->
				</div>
				<!--end::Col-->
				<!--begin::Col-->
				<div class="col-sm-6">
					<!--begin::Wrapper-->
					<div class="d-flex flex-column h-100">
						<!--begin::Header-->
						<div class="mb-7">
							<!--begin::Title-->
							<div class="mb-6">
								<span class="text-gray-400 fs-7 fw-bold me-2 d-block lh-1 pb-1">Tokens : {{ $qrTokens->token }}</span>
								<a href="#" class="text-gray-800 text-hover-primary fs-1 fw-bold">{{ $event->name	 }}</a>
							</div>
							<!--end::Title-->
							<!--begin::Items-->
							<div class="d-flex align-items-center flex-wrap d-grid gap-2">
								<!--begin::Item-->
								<div class="d-flex align-items-center me-5 me-xl-13">
									<!--begin::Symbol-->
									<div class="symbol symbol-30px symbol-circle me-3">
										<img src="{{ asset('storage/images/blank.svg') }}" class="" alt="" />
									</div>
									<!--end::Symbol-->
									<!--begin::Info-->
									<div class="m-0">
										<span class="fw-semibold text-gray-400 d-block fs-8">Locations</span>
										<a href="../../demo8/dist/apps/projects/users.html" class="fw-bold text-gray-800 text-hover-primary fs-7">{{ $event->location }}</a>
									</div>
									<!--end::Info-->
								</div>
								<!--end::Item-->
								<!--begin::Item-->
								<div class="d-flex align-items-center">
									<!--begin::Symbol-->
									<div class="symbol symbol-30px symbol-circle me-3">
										<span class="symbol-label bg-success">
											<i class="ki-duotone ki-abstract-41 fs-5 text-white">
												<span class="path1"></span>
												<span class="path2"></span>
											</i>
										</span>
									</div>
									<!--end::Symbol-->
									<!--begin::Info-->
									<div class="m-0">
										<span class="fw-semibold text-gray-400 d-block fs-8">Date</span>
										<a href="#" class="fw-bold text-gray-800 text-hover-primary fs-7">{{ \Carbon\Carbon::parse($event->date)->translatedFormat('l, j F Y') }}</a>
									</div>
									<!--end::Info-->
								</div>
								<!--end::Item-->
							</div>
							<!--end::Items-->
						</div>
						<!--end::Header-->
						<!--begin::Body-->
						<div class="d-flex flex-column border border-1 border-gray-300 text-center pt-5 pb-7 mb-8 card-rounded">
							<span class="fw-semibold text-gray-600 fs-7 pb-1">Expired At</span>
							<span class="fw-bold text-gray-800 fs-2hx lh-1 pb-1">{{ \Carbon\Carbon::parse($qrTokens->expires_at)->format('H \:\ i') }}</span>
							<span class="fw-bold text-gray-600 fs-4 pb-5">{{ \Carbon\Carbon::parse($qrTokens->expires_at)->translatedFormat('l, j F Y') }}</span>
						</div>
						<!--end::Body-->
						<!--begin::Footer-->
						{{-- <div class="d-flex flex-stack mt-auto bd-highlight">		
							<a href="{{ asset('storage/qr-codes/7.svg') }}" 
								class="btn btn-primary btn-sm flex-shrink-0 me-3" 
								data-bs-toggle="modal" 
								>
									Download QR Code
								</a>
						</div> --}}
						<!--end::Footer-->
					</div>
					<!--end::Wrapper-->
				</div>
				<!--end::Col-->
			</div>
			<!--end::Row-->
		</div>
		<!--end::Body-->
	</div>
</div>
