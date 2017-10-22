import { Component } from "@angular/core";
import { FormBuilder, FormGroup, Validators } from "@angular/forms";
import { AuthService } from "services/user/auth.service";
import { UserAuthForm } from "models/users/user-auth-form.model";
import { LoggerComponent } from "../../../shared/widgets/logger/logger.component";

@Component({
	selector    : "app-login",
	templateUrl : "./login.component.html",
	styleUrls   : [ "./login.component.scss" ],
})
export class LoginComponent {
	public loginForm: FormGroup;

	constructor (private _builder: FormBuilder,
	             private authService: AuthService,
	             private _logger: LoggerComponent) {
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
				console.log("fulfilled");
				console.log(result);
			})
			.catch((error: any) => {
				console.log("rejected");
				console.log(error);
				this._logger.error("error", "error");
			});
	}
}
