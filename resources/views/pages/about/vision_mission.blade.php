@extends('layouts.main')

@section('title')
	Vision and Mission
@endsection

@section('content')
	<div class="global-header" style="background-image: url('{{ asset('img/intro-banner/1.jpg') }}');">
		<div class="global-header__block">
			<div class="va-block">
				<div class="va-middle text-center">
					<h1>Vision and Mission</h1>
				</div>
			</div>
		</div>
	</div>
    <main id="main">
		<div class="container">
			<div class="row">
				<div class="col-md-8">
					<h2>Vision</h2>
					<p>The Diocesan Schools of Bataan envision themselves as a Communion of Educational Institutions committed to form each and everyone of their members to be a HERO.</p>
					<p>(We envision every Diocesan School to be an educational Christian community which integrates the Gospel of Jesus Christ into the life of its members and which offers quality education that will fully develop their human potentials.)</p>

					<h2>Mission</h2>
					<p>The Diocesan Schools of Bataan are committed:</p>
					<ol>
						<li>To provide the total Christian formation of the youth that will lead them to a deeper knowledge and to a closer imitation of the life and mission of Jesus Christ;</li>
						<li>To provide various faith-experiences that will produce Catholics/ Christians who understand and practice their religion;</li>
						<li>To provide competent teachers and instructional facilities that will prepare the students academically for their chosen career/vocation;</li>
						<li>To provide communal activities that will enable the faculty and the	students to become individuals who can lead, relate and respond to the 		needs of the community and the environment.</li>
					</ol>			
					<br>
					<br>
				</div>
				<div class="col-md-4">
                    @include('pages.about.partials.sidebar')
				</div>
			</div>
		</div>
    </main>
@endsection