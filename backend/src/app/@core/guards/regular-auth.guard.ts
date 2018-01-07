import { Injectable } from "@angular/core";
import { CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot, Router } from "@angular/router";
import { AuthService } from "@core/data/users/auth.service";

@Injectable()
export class RegularAuthGuard implements CanActivate {

	constructor ( private _router: Router, private authService: AuthService ) {}

	canActivate ( next: ActivatedRouteSnapshot, state: RouterStateSnapshot ): boolean {
		const url: string = state.url;

		return this.checkLogin(url);
	}

	protected checkLogin ( url: string ): boolean {
		this.authService.redirectUrl = url;

		// TODO locked screen
		/*if (this.authService.isLockedOut()) {
		 this._router.navigate([ "/locked" ]);
		 return false;
		 }*/

		if (this.authService.isLoggedIn()) {
			return true;
		}

		this._router.navigate([ "auth/login" ]);
		return false;
	}
}
