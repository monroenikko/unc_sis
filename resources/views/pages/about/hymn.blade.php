@extends('layouts.main')

@section('title')
	Hymn
@endsection

@section('content')
	<div class="global-header" style="background-image: url('{{ asset('img/intro-banner/1.jpg') }}');">
		<div class="global-header__block">
			<div class="va-block">
				<div class="va-middle text-center">
					<h1>Hymn</h1>
				</div>
			</div>
		</div>
	</div>
    <main id="main">
		<div class="container">
			<div class="row">
				<div class="col-md-8">
					<div class="text-center">
						<p>
							Comrades we gather, let’s get together <br>
							As we greet our loving Alma Mater <br>
							School mates and teachers, parents and founders
						</p>

						<p>
							For this school we’ll bind ourselves together <br>
							Onward we go on, forward we march on <br>
							Long live our school named after Saint John <br>
							Guide us patron Saint John <br>
							Lead the way to march on <br>
							To the light of learning you have shown.
						</p>

						<p>
							Blessed be the day it is founded <br>
							Saint John Academy its name will live forever <br>
							Through its portals we shall pass by <br>
							For better life and greater glory to remember <br>
							Work and play in school we are happy <br>
							No work undone all tasks we ever try <br>
							We bow our heads Saint John Academy <br>
							We praise our patron forever.
						</p>
					</div>
				</div>
				<div class="col-md-4">
                    @include('pages.about.partials.sidebar')
				</div>
			</div>
		</div>
    </main>
@endsection