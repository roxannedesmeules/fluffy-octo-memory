import { Component, OnInit } from "@angular/core";
import { ActivatedRoute } from "@angular/router";
import { Communication } from "@core/data/communication";

import '@theme/icons';

@Component({
	selector    : "app-communication-list",
	templateUrl : "./list.component.html",
	styleUrls   : [ "./list.component.scss" ],
})
export class ListComponent implements OnInit {

	public list: Communication[] = [];

	constructor (private _route: ActivatedRoute) {
	}

	ngOnInit () {
		this._setData();
	}

	private _setData () {
		this.list = this._route.snapshot.data[ "messages" ];
	}
}
