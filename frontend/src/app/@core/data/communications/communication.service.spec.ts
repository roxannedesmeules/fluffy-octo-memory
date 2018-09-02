import { HttpClientTestingModule, HttpTestingController } from "@angular/common/http/testing";
import { TestBed } from "@angular/core/testing";

import { CommunicationService } from "./communication.service";
import { ErrorResponse } from "@core/data/error-response.model";

describe("CommunicationService", () => {
    let service: CommunicationService;
    let httpMock: HttpTestingController;

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports   : [ HttpClientTestingModule ],
            providers : [ CommunicationService ],
        });

        service  = TestBed.get(CommunicationService);
        httpMock = TestBed.get(HttpTestingController);
    });

    afterEach(() => {
        httpMock.verify();
    });

    it("should be created", () => {
        expect(service).toBeTruthy();
    });

    describe("#create", () => {
        it("should return an Observable<> with 204 status", () => {
            service.create({}).subscribe(result => {
                expect(result).toEqual("");
            });

            const req = httpMock.expectOne("communications");

            expect(req.request.method).toBe("POST");

            req.flush('', { status: 204, statusText: "No data" });
        });
    });

    describe("#findAll", () => {
        it("should return a 501 error", () => {
            service.findAll().subscribe(
                    () => {},
                    err => {
                        expect(err).toEqual(jasmine.any(ErrorResponse));
                        expect(err.code).toEqual(501);
                    });
        });
    });

    describe("#findOne", () => {
        it("should return a 501 error", () => {
            service.findOne().subscribe(
                    () => {},
                    err => {
                        expect(err).toEqual(jasmine.any(ErrorResponse));
                        expect(err.code).toEqual(501);
                    });
        });
    });

    describe("#findById", () => {
        it("should return a 501 error", () => {
            service.findById(1).subscribe(
                    () => {},
                    err => {
                        expect(err).toEqual(jasmine.any(ErrorResponse));
                        expect(err.code).toEqual(501);
                    });
        });
    });

    describe("#url", () => {
        it("should transform :baseUrl/:modelName:/:id into communications", () => {
            expect(service.url()).toEqual("communications");
        });
    });
});
