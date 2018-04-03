import { Component, OnInit } from "@angular/core";
import { FormBuilder, FormGroup, Validators } from "@angular/forms";
import { Router } from "@angular/router";
import { UserAuthForm } from "@core/data/users/auth.form";
import { AuthService } from "@core/data/users/auth.service";
import { UserService } from "@core/data/users/user.service";

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
				(result) => {
					console.log(result);
				},
				(error) => {
					console.log(error);
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
