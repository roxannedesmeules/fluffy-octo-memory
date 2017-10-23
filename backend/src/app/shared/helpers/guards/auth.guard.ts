import { Injectable } from "@angular/core";
import { CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot, Router } from "@angular/router";
import { AuthService } from "services/user/auth.service";

@Injectable()
export class AuthGuard implements CanActivate {

	constructor (private _router: Router, private authService: AuthService) {}

	canActivate (next: ActivatedRouteSnapshot, state: RouterStateSnapshot): boolean {
		const url: string = state.url;

		return this.checkLogin(url);
	}

	checkLogin (url: string): boolean {
		// Store the attempted URL for redirecting
		this.authService.redirectUrl = url;

		if (this.authService.isLockedOut()) {
			this._router.navigate([ "/locked" ]);

			return false;
		}

		if (this.authService.isLoggedIn()) {
			return true;
		}

		// Navigate to the login page with extras
		this._router.navigate([ "/login" ]);

		return false;
	}
}
