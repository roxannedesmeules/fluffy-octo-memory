import { Component } from "@angular/core";
import { AuthService } from "services/user/auth.service";
import { Router } from "@angular/router";

@Component({
	selector    : "layout-sidenav",
	templateUrl : "./sidenav.component.html",
	styleUrls   : [ "./sidenav.component.scss" ],
})
export class SidenavComponent {

	categories = [ { name : "create" }, { name : "see all", link : "/categories" }, ];
	posts      = [ { name : "create" }, { name : "see all" }, ];

	constructor (private _router: Router,
	             private authService: AuthService) {
	}

	logout () {
		//  log the user out
		this.authService.logout();

		//  redirect the user to login page
		this._router.navigate([ "/login" ]);
	}

	lockSession () {
		//  lock the user out
		this.authService.lockSession();

		//  redirect the user to lock page
		this._router.navigate([ "/locked" ]);
	}
}
