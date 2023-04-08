import {observeElementOffset, useVirtualizer} from '@tanstack/react-virtual';
import React, {useContext, useEffect, useRef} from 'react';
import {TableRow} from '@common/ui/tables/table-row';
import {TableBodyProps, TableProps} from '@common/ui/tables/table';
import {getScrollParent} from '@react-aria/utils';
import {TableContext} from '@common/ui/tables/table-context';
import {InfiniteScrollSentinel} from '@common/ui/infinite-scroll/infinite-scroll-sentinel';
import {UseInfiniteQueryResult} from '@tanstack/react-query/src/types';

interface VirtualTableBodyProps extends TableBodyProps {
  totalItems?: number;
  query: UseInfiniteQueryResult;
}
export function VirtualTableBody({
  renderRowAs,
  totalItems = 0,
  query,
  hideBorder,
}: VirtualTableBodyProps) {
  const {data} = useContext(TableContext);

  // make sure we are not rendering more placeholder rows than there are items left to lazy load
  const placeholderRowCount = Math.min(totalItems - data.length, 10);

  // only use virtualizer if playlist has more than 3 pages
  return totalItems < 91 ? (
    <Body
      placeholderRowCount={placeholderRowCount}
      renderRowAs={renderRowAs}
      query={query}
      hideBorder={hideBorder}
    />
  ) : (
    <VirtualizedBody
      placeholderRowCount={placeholderRowCount}
      renderRowAs={renderRowAs}
      query={query}
      hideBorder={hideBorder}
    />
  );
}

interface BodyProps {
  renderRowAs?: TableProps<any>['renderRowAs'];
  placeholderRowCount: number;
  query: UseInfiniteQueryResult;
  hideBorder?: boolean;
}
function Body({
  renderRowAs,
  placeholderRowCount,
  query,
  hideBorder,
}: BodyProps) {
  const {data} = useContext(TableContext);
  return (
    <tbody>
      {data.map((track, index) => (
        <TableRow
          item={track}
          index={index}
          key={track.id}
          renderAs={renderRowAs}
          hideBorder={hideBorder}
        />
      ))}
      <Sentinel
        dataCount={data.length}
        placeholderRowCount={placeholderRowCount}
        query={query}
      />
    </tbody>
  );
}

function VirtualizedBody({renderRowAs, placeholderRowCount, query}: BodyProps) {
  const {data} = useContext(TableContext);
  const bodyRef = useRef<HTMLTableSectionElement>(null);
  const scrollableRef = useRef<Element>(null!);
  const scrollOffset = useRef(0);

  useEffect(() => {
    if (bodyRef.current) {
      scrollableRef.current = getScrollParent(bodyRef.current);
      scrollOffset.current =
        bodyRef.current.getBoundingClientRect().top +
        scrollableRef.current.scrollTop;
    }
  }, [bodyRef]);

  const virtualizer = useVirtualizer({
    overscan: 10,
    count: data.length,
    getScrollElement: () => scrollableRef.current,
    estimateSize: () => 48,
    observeElementOffset: (instance, cb) => {
      return observeElementOffset(instance, offset => {
        cb(offset - scrollOffset.current);
      });
    },
  });

  const virtualRows = virtualizer.getVirtualItems();

  const paddingTop = virtualRows.length > 0 ? virtualRows?.[0]?.start || 0 : 0;
  const paddingBottom =
    virtualRows.length > 0
      ? virtualizer.getTotalSize() -
        (virtualRows?.[virtualRows.length - 1]?.end || 0)
      : 0;

  return (
    <tbody ref={bodyRef}>
      {paddingTop > 0 && (
        <tr>
          <td style={{height: `${paddingTop}px`, border: 'none'}} />
        </tr>
      )}
      {virtualRows.map(virtualItem => {
        const item = data[virtualItem.index];
        return (
          <TableRow
            item={item}
            index={virtualItem.index}
            key={item.id}
            renderAs={renderRowAs}
          />
        );
      })}
      <Sentinel
        dataCount={virtualizer.range.endIndex}
        placeholderRowCount={placeholderRowCount}
        query={query}
      />
      {paddingBottom > 0 && (
        <tr>
          <td style={{height: `${paddingBottom}px`, border: 'none'}} />
        </tr>
      )}
    </tbody>
  );
}

interface SentinelProps extends BodyProps {
  dataCount: number;
  query: UseInfiniteQueryResult;
}
function Sentinel({
  dataCount,
  placeholderRowCount,
  renderRowAs,
  query,
}: SentinelProps) {
  if (placeholderRowCount <= 0) {
    return null;
  }
  return (
    <InfiniteScrollSentinel query={query} renderSentinelAs="tr">
      {[...new Array(placeholderRowCount).keys()].map((key, index) => {
        const id = `placeholder-${key}`;
        return (
          <TableRow
            item={{id, isPlaceholder: true}}
            index={dataCount + index}
            key={id}
            renderAs={renderRowAs}
          />
        );
      })}
    </InfiniteScrollSentinel>
  );
}
