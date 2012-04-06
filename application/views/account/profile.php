	<h1>Profile</h1>
	
	<div id="body">
		<a href='edit_profile'>Edit Profile</a> | 
		<a href='change_password'>Change Password</a>
		<hr />
		
		<p>Welcome <b><?=$account['username']?></b> to your profile. What do you want to do?</p>
		<table>
			<tr>
				<td>First Name: <strong><?php if($account['fname'] == NULL):?>[empty]<?php else:?><?=$account['fname']?><?php endif;?></strong></td>
				<td>Last Name: <strong><?php if($account['lname'] == NULL):?>[empty]<?php else:?><?=$account['lname']?><?php endif;?></strong></td>
			</tr>
			<tr>
				<td>Nickname: <strong><?php if($account['nickname'] == NULL):?>[empty]<?php else:?><?=$account['nickname']?><?php endif;?></strong></td>
				<td>Birthdate: <strong><?php if($account['birthdate'] == NULL):?>[empty]<?php else:?><?=$account['birthdate']?><?php endif;?></strong></td>
			</tr>
			<tr>
				<td>Country: <strong><?php if($account['country'] == NULL):?>[empty]<?php else:?><?=$account['country']?><?php endif;?></strong></td>
				<td>City: <strong><?php if($account['city'] == NULL):?>[empty]<?php else:?><?=$account['city']?><?php endif;?></strong></td>
			</tr>
			<tr>
				<td>Artworks: <strong><?php if($account['artworks'] == NULL):?>[empty]<?php else:?><?=$account['artworks']?><?php endif;?></strong></td>
				<td>Max Artworks: <strong><?php if($account['max_artworks'] == NULL):?>[empty]<?php else:?><?=$account['max_artworks']?><?php endif;?></strong></td>
			</tr>
		</table>
	</div>