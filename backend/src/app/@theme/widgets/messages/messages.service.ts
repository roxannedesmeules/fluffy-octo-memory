import { Injectable } from "@angular/core";
import { BehaviorSubject } from "rxjs/BehaviorSubject";
import { Observable } from "rxjs/Observable";

@Injectable()
export class MessagesService {
	private _subject: BehaviorSubject<boolean> = new BehaviorSubject<boolean>(null);

	public getSubject (): Observable<any> {
		return this._subject.asObservable();
	}

	public reload () {
		this._subject.next(true);
	}

}
