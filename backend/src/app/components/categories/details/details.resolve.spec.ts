import { TestBed, inject } from '@angular/core/testing';

import { DetailsResolve } from './details.resolve';

describe('DetailsResolve', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [DetailsResolve]
    });
  });

  it('should be created', inject([DetailsResolve], (service: DetailsResolve) => {
    expect(service).toBeTruthy();
  }));
});
