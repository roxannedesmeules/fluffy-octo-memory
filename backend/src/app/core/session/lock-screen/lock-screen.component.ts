import { Component } from "@angular/core";
import { FormBuilder, FormGroup, Validators } from "@angular/forms";
import { Router } from "@angular/router";
import { LoggerComponent } from "../../../shared/widgets/logger/logger.component";
import { AuthService } from "services/user/auth.service";
import { UserService } from "services/user/user.service";
import { User } from "models/users/user.model";
import { UserAuthForm } from "models/users/user-auth-form.model";
import { ErrorResponse } from "models/error-response.model";

@Component({
	selector    : "app-lock-screen",
	templateUrl : "./lock-screen.component.html",
	styleUrls   : [ "./lock-screen.component.scss" ],
})
export class LockScreenComponent {
	protected _user: User;

	public submitted = false;
	public form: FormGroup;

	constructor (private _builder: FormBuilder,
	             private _router: Router,
	             private _logger: LoggerComponent,
	             private authService: AuthService,
	             private userService: UserService) {

		this._user = this.userService.getAppUser();

		this._createForm();
	}

	private _createForm () {
		this.form = this._builder.group({
			"password" : this._builder.control("", [ Validators.required ]),
			"username" : this._user.username,
		});
	}

	public unlock () {
		this.submitted = true;

		if (this.form.invalid) {
			return;
		}

		this.authService
			.unlockSession(new UserAuthForm(this.form.value))
			.then((result: any) => {
				this.submitted = false;

				this.userService.saveAppUser(this.authService.mapModel(result));
				this._router.navigate([ "/" ]);
			})
			.catch((error: ErrorResponse) => {
				this.submitted = false;

				this._logger.error("Oops..", error.message);
			});
	}
}
