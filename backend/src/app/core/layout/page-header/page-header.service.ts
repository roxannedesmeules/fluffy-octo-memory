import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { Subject } from 'rxjs/Subject';

@Injectable()
export class PageHeaderService {
    private _subject = new Subject<any>();

    getPageHeader (): Observable<any> {
        return this._subject.asObservable();
    }

    setPageHeader (data: any) {
        this._subject.next(data);
    }
}
