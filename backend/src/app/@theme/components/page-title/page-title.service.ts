import { Injectable } from "@angular/core";
import { BehaviorSubject } from "rxjs/BehaviorSubject";
import { Observable } from "rxjs/Observable";

@Injectable()
export class PageTitleService {
	private _subject: BehaviorSubject<string> = new BehaviorSubject<string>(null);

	public getSubject (): Observable<any> {
		return this._subject.asObservable();
	}

	public setTitle ( title: string ) {
		this._subject.next(title);
	}
}
