import { Component } from "@angular/core";
import { FormBuilder, FormGroup, Validators } from "@angular/forms";
import { AuthService } from "services/user/auth.service";
import { UserAuthForm } from "models/users/user-auth-form.model";
import { LoggerComponent } from "../../../shared/widgets/logger/logger.component";
import { ErrorResponse } from "models/error-response.model";
import { Router } from "@angular/router";
import { UserService } from "services/user/user.service";

@Component({
	selector    : "app-login",
	templateUrl : "./login.component.html",
	styleUrls   : [ "./login.component.scss" ],
})
export class LoginComponent {
	public loginForm: FormGroup;

	constructor (private _builder: FormBuilder,
	             private _logger: LoggerComponent,
	             private _router: Router,
	             private authService: AuthService,
	             private userService: UserService) {
		this._buildform();
	}

	private _buildform () {
		this.loginForm = this._builder.group({
			username : this._builder.control("", [ Validators.required ]),
			password : this._builder.control("", [ Validators.required ]),
		});
	}

	public login () {
		if (this.loginForm.invalid) {
			return;
		}

		this.authService
			.login(new UserAuthForm(this.loginForm.value))
			.then((result: any) => {
				this.userService.saveAppUser(this.authService.mapModel(result));
				this._router.navigate([ "/" ]);
			})
			.catch((error: ErrorResponse) => {
				this._logger.error("", error.message);
			});
	}
}
