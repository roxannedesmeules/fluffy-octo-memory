import { Component, OnInit } from "@angular/core";
import { User } from "@core/data/users/user.model";
import { UserService } from "@core/data/users/user.service";

@Component({
	selector    : "app-widgets-user",
	templateUrl : "./user.component.html",
	styleUrls   : [ "./user.component.scss" ],
})
export class UserComponent implements OnInit {

	public user: User;

	constructor (private userService: UserService) {}

	ngOnInit () {
		this._getUser();
	}

	private _getUser() {
		this.user = this.userService.getAppUser();
	}
}
