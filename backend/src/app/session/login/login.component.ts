import { Component, OnInit } from "@angular/core";
import { FormBuilder, FormGroup, Validators } from "@angular/forms";
import { Router } from "@angular/router";
import { ErrorResponse } from "@core/data/error-response.model";
import { UserAuthForm } from "@core/data/users/auth.form";
import { AuthService } from "@core/data/users/auth.service";
import { User } from "@core/data/users/user.model";
import { UserService } from "@core/data/users/user.service";
import { LoggerService } from "../../@shared/logger/logger.service";

@Component({
	selector    : "app-session-login",
	templateUrl : "./login.component.html",
	styleUrls   : [ "./login.component.scss" ],
})
export class LoginComponent implements OnInit {

	public form: FormGroup;

	public submitted: boolean = false;
	public messages: string[] = [];
	public errors: string[]   = [];

	constructor ( private _builder: FormBuilder,
				  private _router: Router,
				  private _logger: LoggerService,
				  private authService: AuthService,
				  private userService: UserService) {
	}

	ngOnInit () {
		this._createForm();
	}

	/**
	 * Build login form group
	 *
	 * @private
	 */
	private _createForm () {
		this.form = this._builder.group({
			username : this._builder.control("", [ Validators.required ]),
			password : this._builder.control("", [ Validators.required ]),
		});
	}

	/**
	 *
	 */
	public login () {
		this._reset();

		this.submitted = true;

		if (this.form.invalid) {
			return;
		}

		this.authService
			.login(new UserAuthForm(this.form.getRawValue()))
			.subscribe(
				(result: User) => {
					this.submitted = false;

					this.userService.saveAppUser(result);
					this._router.navigate([ "/" ]);
				},
				(error: ErrorResponse) => {
					this.submitted = false;

					this._logger.error(error.shortMessage);
				}
			);
	}

	/**
	 * Reset errors and messages
	 *
	 * @private
	 */
	private _reset () {
		this.errors   = [];
		this.messages = [];
	}
}
