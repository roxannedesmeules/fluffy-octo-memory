import { Component, EventEmitter, Input, Output } from "@angular/core";
import { Router } from "@angular/router";
import { AuthService } from "@core/data/users";

@Component({
	selector    : "app-layout-header",
	templateUrl : "./header.component.html",
	styleUrls   : [ "./header.component.scss" ],
})
export class HeaderComponent {

	@Input() isShrinked:boolean = false;
	@Output() toggleSidemenu: EventEmitter<boolean> = new EventEmitter();

	constructor ( private _router: Router, private service: AuthService ) {}

	public logout () {
		this.service.logout()
			.subscribe(
				() => { this._router.navigate([ "/auth/login" ]); },
				() => { this._router.navigate([ "/auth/login" ]); }
			);
	}
}
