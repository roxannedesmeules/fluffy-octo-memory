import { inject, TestBed } from "@angular/core/testing";

import { DetailResolve } from "./detail.resolve";

describe("DetailsResolve", () => {
    beforeEach(() => {
        TestBed.configureTestingModule({
            providers : [ DetailResolve ],
        });
    });

    it("should be created", inject([ DetailResolve ], (service: DetailResolve) => {
        expect(service).toBeTruthy();
    }));
});
