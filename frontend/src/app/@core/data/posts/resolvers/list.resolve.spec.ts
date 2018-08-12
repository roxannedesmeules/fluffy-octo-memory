import { inject, TestBed } from "@angular/core/testing";

import { ListResolve } from "./list.resolve";

describe("ListResolve", () => {
    beforeEach(() => {
        TestBed.configureTestingModule({
            providers : [ ListResolve ],
        });
    });

    it("should be created", inject([ ListResolve ], (service: ListResolve) => {
        expect(service).toBeTruthy();
    }));
});
