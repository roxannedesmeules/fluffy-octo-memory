import { TestBed, inject } from '@angular/core/testing';

import { PostCoverService } from './post-cover.service';

describe('PostCoverService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [PostCoverService]
    });
  });

  it('should be created', inject([PostCoverService], (service: PostCoverService) => {
    expect(service).toBeTruthy();
  }));
});
