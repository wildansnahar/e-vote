<?php
 		session_start();
 		?>
<body>

<link rel="stylesheet" type="text/css" href="side-content.css">
<div class="main-content">

	<div class="sidebar">
		<ul>
				<li> <a href="/index.php" title="Back to home"> <img src="img/home.svg">	</a>	</li>
				<li> <a href="/history.php" title="See the history"> <img src="img/history.svg">	</a>	</li> 
				<li> <a href="/candidates.php" title="Who are the candidates ?"> <img src="img/candidates.svg"> </a>	</li> 
				<li> <a href="/rules.php" title="Read the rules">	<img src="img/rules.svg"> 		</a>	</li> 
				<li> <a href="/sign-up.php" title="Sign yourself here"> <img src="img/sign-up.svg">		</a>	</li> 
				<li> <a href="/log-in.php" title="Get in here"> <img src="img/log-in.svg">	</a>	</li> 
				<li> <a href="/vote.php" title="Vote! here"> <img src="img/vote.svg">	</a>	</li>
				<li> <a href="/about.php" title="About this web"> <img src="img/about.svg"> 		</a>	</li> 
				<?php
 		 		 		if(isset($_SESSION["id"])) {

				echo "<li> <a href='/logout.php' title='Log out'> <img src='img/logout.svg'> 		</a>	</li>";
				}	
				?> 	
			</ul> 
			<br><br>
			<!-- CONTACT PERSON -->
			
			<div class="contact">
				<div1>
				<strong> CONTACT US	</strong>	<br>
			</div1>
			<p>	Sekretariat ARC ITB 			<br>
				Sunken Court W-05				<br>
				E-mail :<a href="mailto:pemilu@arc.itb.ac.id"> pemilu@arc.itb.ac.id 	</a><br>
			</p>
			</div>
	</div>

 	<div class="content">
 		<p> You are here : Home > <strong> Candidates </strong>	
 		</p>
 		<h1> CALON KETUA ARC
 		</h1>
 		<div class="candidates">
 			<center>
 			<figure>
 			<a href="img/banner.png" target="_blank"> <img src="img/wall.png" alt="banner" title="click to zoom in"> 
			<figcaption> Spoiler kandidat Ketua ARC 2015
			</figcaption>
 			</a>
 			</figure>
 			</center>
 			<div class="visi">
 			<p>
 				VISI - MISI
 				<ul>
 					<li>
 						Presentasi Visi-Misi WAHYU Muqsita <a href="docs/wahyu.pptx"> klik disini </a>
 					</li>
 					<li>
 						Presentasi Visi-Misi SYAIFUL Andy <a href="docs/syaiful.pptx"> klik disini </a>
 					</li>
 				</ul>
 			</p>
 			</div>
 		</div>
	</div>

</div>
</body>
