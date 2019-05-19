@extends('layouts.main')

@section('title')
	History
@endsection

@section('content')
	<div class="global-header" style="background-image: url('{{ asset('img/intro-banner/1.jpg') }}');">
		<div class="global-header__block">
			<div class="va-block">
				<div class="va-middle text-center">
					<h1>History</h1>
				</div>
			</div>
		</div>
	</div>
    <main id="main">
		<div class="container">
			<div class="row">
				<div class="col-md-8">
					<p>SAINT JOHN ACADEMY was born out of a clamor for a Catholic educational institution which will provide  a deeply-rooted Christian formation to the young and which can supply the  volunteers for the Parochial catechetical program at the public schools within the parish.</p>
					<p>Msgr. Florentino F. Guiao founded SAINT JOHN ACADEMY in June 1960. The new parish priest, with the support of laity, realized the dream of the community for a Catholic educational institution.</p>
					<p>The new academy, following the example  of its patron saint, has proven its worth in its scholastic performance in different competitions and in its inspiring religious activities within the parish community. After being recognized as a government mandated Private  School the dynamism of its operation produced successful graduates, nurtured with Christian values.</p>
					<p>SAINT JOHN ACADEMY came into existence not merely to provide quality education but more importantly, Christian formation to its students.  Various religious educations such as the Student Catholic Action, Children of Mary and Legion of Mary were established to  initiate students into the apostolic life of the Church.  However, what was considered as the most significant contribution of the academy of the parish was the catechetical program wherein its junior and senior students  taught the Catholic Faith in the elementary schools of the parish.</p>
					<p>SAINT JOHN ACADEMY is called by the Diocese of Balanga to widen its vision and share in the threefold purpose of Catholic education:  to proclaim to the students the Gospel of Jesus Christ ( KERYGMA ); to enable them to experience fellowship in the life of the Holy Spirit ( KOINONIA) ; and to prepare them to a life service to the Christian community and the entire human race ( DIAKONIA )</p>
					<p>It is only in sharing in this total mission of the church that SAINT JOHN ACADEMY becomes a true Catholic educational institution.  As such, its operation and progress deserve to engage the joint participation of families, teachers, various kinds of cultural, civic, and religious groups, civil society and invaluable work for the church.   </p>		
				</div>
				<div class="col-md-4">
                    @include('pages.about.partials.sidebar')
				</div>
			</div>
		</div>
    </main>
@endsection